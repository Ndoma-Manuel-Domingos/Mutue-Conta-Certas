<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Contrapartida;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Exercicio;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\SubConta;
use App\Models\Taxa;
use App\Models\TipoCredito;
use App\Models\TipoMovimento;
use App\Models\TipoProveito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Inertia\Inertia;
use PDF;

class FluxoCaixaController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
        $data['fluxos_caixas'] = [];
        $data['exercicios'] = Exercicio::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        $data['periodos'] = Periodo::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        
        $data['movimentos'] = Movimento::when($request->exercicio_id, function($query, $value){
            $query->where('exercicio_id', $value);
        })
        ->when($request->periodo_id, function($query, $value){
            $query->where('periodo_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('data_lancamento',  ">=" ,$value);
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('data_lancamento', "<=" ,$value);
        })
        ->with(['items', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'])
        ->where('origem', 'fluxocaixa')
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')
        ->get();
        
        return Inertia::render('FluxoCaixa/Index', $data);
    }
    
    //
    public function demonstracaoFluxoCaixa(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['exercicios'] = Exercicio::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        $data['periodos'] = Periodo::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        
        // Dinheiro recebido de Clientes
        $conta_cliente = Conta::where('numero', '31')->first();
        $data['dinheiro_recebido_clientes'] = MovimentoItem::where('origem', 'fluxocaixa')->where('conta_id', $conta_cliente->id)->sum('debito');
        
        // Dinheiro pagos em mercadorias
        $conta_fornecedore = Conta::where('numero', '32')->first();
        $data['dinheiro_recebido_fornecedores'] = MovimentoItem::where('origem', 'fluxocaixa')->where('conta_id', $conta_fornecedore->id)->sum('debito');
       
        // Dinheiro pagos em salários e custos operacionais conta 75
        $conta_custo_operacionais = Conta::where('numero', '75')->first();
        $subconta_custo_operacionais = SubConta::where('tipo', 'M')->where('numero', 'like', '75.2.'. "%")->where('conta_id', $conta_custo_operacionais->id)->pluck('id');
        
        $conta_salario = Conta::where('numero', '72')->first();
        $subconta_salario = SubConta::where('tipo', 'M')->where('numero', 'like', '72.2.'. "%")->orWhere('numero', 'like', '72.1.'. "%")->where('conta_id', $conta_salario->id)->pluck('id');
        
        $dinheiro_subconta_custo_operacionais = MovimentoItem::where('origem', 'fluxocaixa')->whereIn('subconta_id', $subconta_custo_operacionais)->sum('debito');
        $dinheiro_subconta_salario = MovimentoItem::where('origem', 'fluxocaixa')->whereIn('subconta_id', $subconta_salario)->sum('debito');
        
        $data['dinheiro_custo'] = $dinheiro_subconta_custo_operacionais + $dinheiro_subconta_salario;
        $data['outros'] = 0;
        
        //Dinheiro pagos em juros
        $data['dinheiro_pagos_juros'] = 0;
        
        // Dinheiro pagos em impostos
        $conta_imposto = Conta::where('numero', '34')->first();
        $subconta_imposto = SubConta::where('tipo', 'M')->where('numero', 'like', '34.1.'. "%")
            ->orWhere('numero', 'like', '34.2.'. "%")
            ->orWhere('numero', 'like', '34.3.'. "%")
            ->orWhere('numero', 'like', '34.4.'. "%")
            ->orWhere('numero', 'like', '34.5.'. "%")
            ->orWhere('numero', 'like', '34.9.'. "%")
            ->where('conta_id', $conta_imposto->id)->pluck('id');
        
        $data['dinheiro_imposto'] = MovimentoItem::where('origem', 'fluxocaixa')->whereIn('subconta_id', $subconta_imposto)->sum('debito');
        
        return Inertia::render('FluxoCaixa/Demonstracao', $data);
    }

    public function create(Request $request)
    {
        // Retorna a lista de posts
        $user = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
        $data['fluxos_caixas'] = [];
        
        $conta = Conta::where('numero', '45')->first();
        
        $data['subcontas'] = SubConta::where('tipo', 'M')->where('conta_id', $conta->id)->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->orderBy('numero','asc')->get();
        $data['documentos'] = Documento::select('id', 'designacao AS text')->get();
        $data['tipo_movimentos'] = TipoMovimento::select('id', 'designacao AS text')->orderBy('id', 'desc')->get();
        $data['tipo_creditos'] = TipoCredito::select('id', 'designacao AS text')->get();
        $data['tipo_proveitos'] = TipoProveito::select('id', 'designacao AS text')->get();
        $data['taxas'] = Taxa::select('id', 'designacao AS text')->get();
        
        $data['saldo'] = MovimentoItem::with(['conta'])
        ->select('conta_id', 'empresa_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->where('conta_id', $conta->id)
        ->groupBy('conta_id', 'empresa_id')
        ->where('empresa_id', $this->empresaLogada())
        ->first();
  
        $data['contrapartidas'] = Contrapartida::when($request->tipo_credito_id, function($query, $value){
            $query->where('tipo_credito_id' ,$value);
        })
        ->join('sub_contas', 'contrapartidas.sub_conta_id', '=', 'sub_contas.id')
        ->select('contrapartidas.id', 'contrapartidas.tipo_credito_id', DB::raw('CONCAT(sub_contas.numero, " - ", sub_contas.designacao) AS text'))->get();
                
        $data['item_movimentos'] = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            
        $data['resultados'] = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return Inertia::render('FluxoCaixa/Create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
     
        $codigo = time();
        
        $resultado = MovimentoItem::with(['subconta.conta', 'empresa'])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito, SUM(iva) AS total_iva')->first();
        
        $ultimo_movimento = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->where('empresa_id', $this->empresaLogada())->count();
         
        $create = Movimento::create([
            'hash' => $codigo,
            'debito' => $resultado->total_debito,
            'credito' => $resultado->total_credito,
            'iva' => $resultado->total_iva,
            'empresa_id' => $this->empresaLogada(),
            'descricao' => "",
            'origem' => "fluxocaixa",
            'exercicio_id' => $this->exercicioActivo(),
            'periodo_id' =>  $this->periodoActivo(),
            'dia_id' => date("d"),
            'data_lancamento' => date("Y-m-d"),
            'lancamento_atual' => $ultimo_movimento + 1,
            'diario_id' => 2, // $request->diario_id,
            'tipo_documento_id' => 2, //$request->tipo_documento_id,
            'user_id' => $user->id,
            'created_by' => $user->id,
        ]);
        
        $items = MovimentoItem::with(['subconta.conta', 'empresa'])->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        foreach ($items as $item) {
            $update = MovimentoItem::findOrFail($item->id);
            $update->hash = $codigo;
            $update->movimento_id = $create->id;
            $update->update();
        }
            
    }
    
    public function adicionar_fluxo_caixa(Request $request)
    {
         
        
        $user = User::findOrFail(auth()->user()->id); 
    
        $tipo_proveito = TipoProveito::find($request->tipo_proveito_id);
        $tipo_credito = TipoCredito::find($request->tipo_credito_id);
        $contrapartida = Contrapartida::find($request->contrapartida_id);
        $documento = Documento::find($request->documento_id);
        $subconta = SubConta::find($request->sub_conta_id);
        $tipo_movimento = TipoMovimento::find($request->tipo_movimento_id);
        
        $subconta_do_iva = SubConta::where('numero', '34.5.2')->orWhere('numero', '34.5.2.2')->first();
        $subconta_do_iva_debito = SubConta::where('numero', '34.5.3')->orWhere('numero', '34.5.3')->first();
        $taxa_iva = Taxa::find($request->taxa_iva_id);
        
        $novo_valor_com_iva = (($request->valor ?? 0) * (($taxa_iva->taxa ?? 0) / 100));
          
        try {
            // Iniciar a transação
            DB::beginTransaction();
            // Executar operações no banco de dados dentro da transação
                        
            if($tipo_movimento->sigla == "D"){
                
                // SE ESTIVEMOS A DEBITAR ENTÃO VAMOS VER O TIPO DE PROVEITO
                // CASO O TIPO DE PROVEITO FOR PRESTAÇÃO DE SERVICO VAMOS CREDITAR NA CONTA 62
                // CASO O TIPO DE PROVEITO FOR VENDA DE PRODUTO VAMOS CREDITAR NA CONTA 61
                
                // 
                
                $hash = time();
            
                $verificar_movimento = MovimentoItem::where('movimento_id', NULL)->where('origem', 'fluxocaixa')->where('user_id', $user->id)->where('empresa_id', $this->empresaLogada())->first();
                
                if($verificar_movimento){
                    MovimentoItem::create([
                        'hash' => $verificar_movimento->hash,
                        'debito' => $request->valor + $novo_valor_com_iva,
                        'credito' => 0,
                        'iva' => $novo_valor_com_iva,
                        'descricao' => $request->designacao,
                        'origem' => "fluxocaixa",
                        'empresa_id' => $this->empresaLogada(),
                        'subconta_id' => $subconta->id,
                        'conta_id' => $subconta->conta_id,
                        'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'apresentar' => 'S',
                        'movimento_id' => NULL,
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                    
                    
                    $tipo_movimento_c = TipoMovimento::where('sigla', 'C')->first();
                    
                    if($tipo_proveito->sigla == "P"){
                        
                        // Retirar no conta 61
                        $subconta_movimentar = SubConta::where('numero', '61.1.1')->where('tipo', 'M')->first();
                        
                        if($subconta_movimentar){
                            MovimentoItem::create([
                                'hash' => $verificar_movimento->hash,
                                'debito' => 0,
                                'credito' => $request->valor,
                                'iva' => 0,
                                'descricao' => $request->designacao,
                                'empresa_id' => $this->empresaLogada(),
                                'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                'documento_id' => $documento ? $documento->id : NULL,
                                'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                'taxta_iva_id' => $taxa_iva->id ?? 1,
                                'origem' => 'fluxocaixa',
                                'movimento_id' => NULL,
                                'apresentar' => 'N',
                                'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                'user_id' => $user->id,
                                'created_by' => $user->id,
                            ]);
                            
                            if($novo_valor_com_iva > 0){
                                MovimentoItem::create([
                                    'hash' => $verificar_movimento->hash,
                                    'debito' => 0,
                                    'credito' => $novo_valor_com_iva,
                                    'iva' => 0,
                                    'descricao' => $request->designacao,
                                    'empresa_id' => $this->empresaLogada(),
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'subconta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->id : NULL,
                                    'conta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->conta_id : NULL,
                                    // 'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                    // 'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                    'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                    'documento_id' => $documento ? $documento->id : NULL,
                                    'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                    'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                    'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'origem' => 'fluxocaixa',
                                    'movimento_id' => NULL,
                                    'apresentar' => 'N',
                                    'user_id' => $user->id,
                                    'created_by' => $user->id,
                                ]);
                            }
                        }
                        
                    }
                    
                    if($tipo_proveito->sigla == "S"){
                        // Retirar no conta 62
                        $subconta_movimentar = SubConta::where('numero', '62.1.1')->where('tipo', 'M')->first();
                        
                        if($subconta_movimentar){
                            MovimentoItem::create([
                                'hash' => $verificar_movimento->hash,
                                'debito' => 0,
                                'credito' => $request->valor,
                                'iva' => 0,
                                'descricao' => $request->designacao,
                                'empresa_id' => $this->empresaLogada(),
                                'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                'documento_id' => $documento ? $documento->id : NULL,
                                'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                'taxta_iva_id' => $taxa_iva->id ?? 1,
                                'origem' => 'fluxocaixa',
                                'movimento_id' => NULL,
                                'apresentar' => 'N',
                                'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                'user_id' => $user->id,
                                'created_by' => $user->id,
                            ]);
                            
                            if($novo_valor_com_iva > 0){
                                MovimentoItem::create([
                                    'hash' => $verificar_movimento->hash,
                                    'debito' => 0,
                                    'credito' => $novo_valor_com_iva,
                                    'iva' => 0,
                                    'descricao' => $request->designacao,
                                    'empresa_id' => $this->empresaLogada(),
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'subconta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->id : NULL,
                                    'conta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->conta_id : NULL,
                                    // 'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                    // 'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                    'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                    'documento_id' => $documento ? $documento->id : NULL,
                                    'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                    'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                    'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'origem' => 'fluxocaixa',
                                    'movimento_id' => NULL,
                                    'apresentar' => 'N',
                                    'user_id' => $user->id,
                                    'created_by' => $user->id,
                                ]);
                            }
                        }
                        
                    }
                    
                    // dd("dentro antigo");  
                }else{
                    
                    MovimentoItem::create([
                        'hash' => $hash,
                        'debito' => $request->valor + $novo_valor_com_iva,
                        'credito' => 0,
                        'iva' => 0,
                        'descricao' => $request->designacao,
                        'origem' => "fluxocaixa",
                        'empresa_id' => $this->empresaLogada(),
                        'subconta_id' => $subconta->id,
                        'conta_id' => $subconta->conta_id,
                        'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'apresentar' => 'S',
                        'movimento_id' => NULL,
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                    
                    $tipo_movimento_c = TipoMovimento::where('sigla', 'C')->first();
                    
                    if($tipo_proveito->sigla == "P"){
                        // Retirar no conta 61
                        $subconta_movimentar = SubConta::where('numero', '61.1.1')->where('tipo', 'M')->first();
                        if($subconta_movimentar){
                            MovimentoItem::create([
                                'hash' => $hash,
                                'debito' => 0,
                                'credito' => $request->valor,
                                'iva' => 0,
                                'descricao' => $request->designacao,
                                'empresa_id' => $this->empresaLogada(),
                                'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                'documento_id' => $documento ? $documento->id : NULL,
                                'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                'taxta_iva_id' => $taxa_iva->id ?? 1,
                                'origem' => 'fluxocaixa',
                                'movimento_id' => NULL,
                                'apresentar' => 'N',
                                'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                'user_id' => $user->id,
                                'created_by' => $user->id,
                            ]);
                            if($novo_valor_com_iva > 0){
                                MovimentoItem::create([
                                    'hash' => $hash,
                                    'debito' => 0,
                                    'credito' => $novo_valor_com_iva,
                                    'iva' => 0,
                                    'descricao' => $request->designacao,
                                    'empresa_id' => $this->empresaLogada(),
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'subconta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->id : NULL,
                                    'conta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->conta_id : NULL,
                                    // 'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                    // 'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                    'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                    'documento_id' => $documento ? $documento->id : NULL,
                                    'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                    'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                    'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'origem' => 'fluxocaixa',
                                    'movimento_id' => NULL,
                                    'apresentar' => 'N',
                                    'user_id' => $user->id,
                                    'created_by' => $user->id,
                                ]);
                            
                            }
                        }
                    }
                    
                    if($tipo_proveito->sigla == "S"){
                        // Retirar no conta 62
                        $subconta_movimentar = SubConta::where('numero', '62.1.1')->where('tipo', 'M')->first();
                        
                        if($subconta_movimentar){
                            MovimentoItem::create([
                                'hash' => $hash,
                                'debito' => 0,
                                'credito' => $request->valor,
                                'iva' => 0,
                                'descricao' => $request->designacao,
                                'empresa_id' => $this->empresaLogada(),
                                'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                'documento_id' => $documento ? $documento->id : NULL,
                                'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                'taxta_iva_id' => $taxa_iva->id ?? 1,
                                'origem' => 'fluxocaixa',
                                'movimento_id' => NULL,
                                'apresentar' => 'N',
                                'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                'user_id' => $user->id,
                                'created_by' => $user->id,
                            ]);
                            
                            if($novo_valor_com_iva > 0){
                                MovimentoItem::create([
                                    'hash' => $hash,
                                    'debito' => 0,
                                    'credito' => $novo_valor_com_iva,
                                    'iva' => 0,
                                    'descricao' => $request->designacao,
                                    'empresa_id' => $this->empresaLogada(),
                                    
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'subconta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->id : NULL,
                                    'conta_id' => $subconta_do_iva_debito ? $subconta_do_iva_debito->conta_id : NULL,
                                    
                                    // 'subconta_id' => $subconta_movimentar ? $subconta_movimentar->id : NULL,
                                    // 'conta_id' => $subconta_movimentar ? $subconta_movimentar->conta_id : NULL,
                                    'tipo_movimento_id' => $tipo_movimento_c ? $tipo_movimento_c->id : NULL,
                                    'documento_id' => $documento ? $documento->id : NULL,
                                    'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                                    'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                                    'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                                    'taxta_iva_id' => $taxa_iva->id ?? 1,
                                    'origem' => 'fluxocaixa',
                                    'movimento_id' => NULL,
                                    'apresentar' => 'N',
                                    'user_id' => $user->id,
                                    'created_by' => $user->id,
                                ]);
                            
                            }
                        }
                    }
                    
                }
                
     
            }else if($tipo_movimento->sigla == "C"){
                
                // SE ESTIVEMOS A  CRÉDITAR ENTÃO VAMOS DEBITA EM UMA CONTRAPARTIDA
                $hash = time();
            
                $verificar_movimento = MovimentoItem::where('movimento_id', NULL)->where('origem', 'fluxocaixa')->where('user_id', $user->id)->where('empresa_id', $this->empresaLogada())->first();
                
                if($verificar_movimento){
                     
                    $contrapartida = Contrapartida::find($request->contrapartida_id);
                    $subconta = SubConta::find($contrapartida->sub_conta_id);
                    $subconta_principal = SubConta::find($request->sub_conta_id);
                    
                    MovimentoItem::create([
                        'hash' => $verificar_movimento->hash,
                        'debito' => 0,
                        'credito' => $request->valor + $novo_valor_com_iva,
                        'iva' => $novo_valor_com_iva,
                        'descricao' => $request->designacao,
                        'origem' => "fluxocaixa",
                        'empresa_id' => $this->empresaLogada(),
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'subconta_id' => $subconta_principal->id,
                        'conta_id' => $subconta_principal->conta_id,
                        'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'apresentar' => 'S',
                        'movimento_id' => NULL,
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                    
                    $tipo_movimento_d = TipoMovimento::where('sigla', 'D')->first();
                    
                    MovimentoItem::create([
                        'hash' => $verificar_movimento->hash,
                        'debito' => $request->valor,
                        'credito' => 0,
                        'iva' => 0,
                        'descricao' => $request->designacao,
                        'empresa_id' => $this->empresaLogada(),
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'subconta_id' => $subconta ? $subconta->id : NULL,
                        'conta_id' => $subconta ? $subconta->conta_id : NULL,
                        'tipo_movimento_id' => $tipo_movimento_d ? $tipo_movimento_d->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'origem' => 'fluxocaixa',
                        'movimento_id' => NULL,
                        'apresentar' => 'N',
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                    
                    if($novo_valor_com_iva > 0){
                        MovimentoItem::create([
                            'hash' => $verificar_movimento->hash,
                            'debito' => $novo_valor_com_iva,
                            'credito' => 0,
                            'iva' => 0,
                            'descricao' => $request->designacao,
                            'empresa_id' => $this->empresaLogada(),
                            'taxta_iva_id' => $taxa_iva->id ?? 1,
                            'subconta_id' => $subconta_do_iva ? $subconta_do_iva->id : NULL,
                            'conta_id' => $subconta_do_iva ? $subconta_do_iva->conta_id : NULL,
                            'tipo_movimento_id' => $tipo_movimento_d ? $tipo_movimento_d->id : NULL,
                            'documento_id' => $documento ? $documento->id : NULL,
                            'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                            'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                            'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                            'origem' => 'fluxocaixa',
                            'movimento_id' => NULL,
                            'apresentar' => 'N',
                            'user_id' => $user->id,
                            'created_by' => $user->id,
                        ]);
                    }
                    
                }else{
                                    
                    $contrapartida = Contrapartida::find($request->contrapartida_id);
                    $subconta = SubConta::find($contrapartida->sub_conta_id);
                    $subconta_principal = SubConta::find($request->sub_conta_id);
                
                    MovimentoItem::create([
                        'hash' => $hash,
                        'debito' => 0,
                        'credito' => $request->valor + $novo_valor_com_iva,
                        'iva' => $novo_valor_com_iva,
                        'descricao' => $request->designacao,
                        'empresa_id' => $this->empresaLogada(),
                        'conta_id' => $subconta_principal ? $subconta_principal->conta_id : NULL,
                        'subconta_id' => $subconta_principal ? $subconta_principal->id : NULL,
                        'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'origem' => 'fluxocaixa',
                        'movimento_id' => NULL,
                        'apresentar' => 'S',
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                    
                    $tipo_movimento_d = TipoMovimento::where('sigla', 'D')->first();
                    
                    MovimentoItem::create([
                        'hash' => $hash,
                        'debito' => $request->valor,
                        'credito' => 0,
                        'iva' => 0,
                        'descricao' => $request->designacao,
                        'empresa_id' => $this->empresaLogada(),
                        'subconta_id' => $subconta ? $subconta->id : NULL,
                        'conta_id' => $subconta ? $subconta->conta_id : NULL,
                        'tipo_movimento_id' => $tipo_movimento_d ? $tipo_movimento_d->id : NULL,
                        'documento_id' => $documento ? $documento->id : NULL,
                        'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                        'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                        'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                        'taxta_iva_id' => $taxa_iva->id ?? 1,
                        'origem' => 'fluxocaixa',
                        'movimento_id' => NULL,
                        'apresentar' => 'N',
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                    ]);
                                        
                    if($novo_valor_com_iva > 0){
                        MovimentoItem::create([
                            'hash' => $hash,
                            'debito' => $novo_valor_com_iva,
                            'credito' => 0,
                            'iva' => 0,
                            'descricao' => $request->designacao,
                            'empresa_id' => $this->empresaLogada(),
                            'subconta_id' => $subconta_do_iva ? $subconta_do_iva->id : NULL,
                            'conta_id' => $subconta_do_iva ? $subconta_do_iva->conta_id : NULL,
                            'tipo_movimento_id' => $tipo_movimento_d ? $tipo_movimento_d->id : NULL,
                            'documento_id' => $documento ? $documento->id : NULL,
                            'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                            'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                            'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                            'taxta_iva_id' => $taxa_iva->id ?? 1,
                            'origem' => 'fluxocaixa',
                            'movimento_id' => NULL,
                            'apresentar' => 'N',
                            'user_id' => $user->id,
                            'created_by' => $user->id,
                        ]);
                    }
                }
            }
            // Confirmar a transação
            DB::commit();
            
            $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            
            $resultados = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
            
            return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
        
        } catch (QueryException $e) {
            // Se ocorrer um erro, reverter a transação
            DB::rollBack();
        
            // Lidar com o erro de alguma maneira, como exibir uma mensagem de erro
            // echo "Erro: " . $e->getMessage();
        }
        
  
    }

    public function editar_fluxo_caixa(Request $request, $id)
    {
        $item1 = MovimentoItem::findOrFail($id); 
        $user = User::findOrFail(auth()->user()->id); 
        
        $tipo_proveito = TipoProveito::find($request->tipo_proveito_id);
        $tipo_credito = TipoCredito::find($request->tipo_credito_id);
        $contrapartida = Contrapartida::find($request->contrapartida_id);
        $documento = Documento::find($request->documento_id);
        $subconta = SubConta::find($request->sub_conta_id);
        $tipo_movimento = TipoMovimento::find($request->tipo_movimento_id);
        
        try {
            // Iniciar a transação
            DB::beginTransaction();
            // Executar operações no banco de dados dentro da transação
            $item1 = MovimentoItem::findOrFail($id);   
            $item2 = MovimentoItem::findOrFail($id+1);   
            
            if($tipo_movimento->sigla == "D"){
            
                $item1->debito = $request->valor;
                $item1->credito = 0;
                $item1->iva = 0;
                $item1->descricao = $request->designacao;
                $item1->subconta_id = $subconta->id;
                $item1->conta_id = $subconta->conta_id;
                $item1->tipo_movimento_id = $tipo_movimento ? $tipo_movimento->id : NULL;
                $item1->documento_id = $documento ? $documento->id : NULL;
                $item1->tipo_proveito_id = $tipo_proveito ? $tipo_proveito->id : NULL;
                $item1->tipo_credito_id = NULL;
                $item1->contrapartida_id = NULL;
                // $item1->tipo_credito_id = $tipo_credito ? $tipo_credito->id : NULL;
                // $item1->contrapartida_id = $contrapartida ? $contrapartida->id : NULL;
                $item1->updated_by = $user->id;
                $item1->update();
            
                $tipo_movimento_c = TipoMovimento::where('sigla', 'C')->first();
                
                if($tipo_proveito->sigla == "P"){
                    
                    // Retirar no conta 61
                    $subconta_movimentar = SubConta::where('numero', '61.1.1')->where('tipo', 'M')->first();
                    
                    if($subconta_movimentar){
                        $item2->debito = 0;
                        $item2->credito = $request->valor;
                        $item2->iva = 0;
                        $item2->descricao = $request->designacao;
                        $item2->subconta_id = $subconta_movimentar ? $subconta_movimentar->id : NULL;
                        $item2->tipo_movimento_id = $tipo_movimento_c ? $tipo_movimento_c->id : NULL;
                        $item2->documento_id = $documento ? $documento->id : NULL;
                        $item2->tipo_proveito_id = $tipo_proveito ? $tipo_proveito->id : NULL;
                        $item2->tipo_credito_id = NULL;
                        $item2->contrapartida_id = NULL;
                        // $item2->tipo_credito_id = $tipo_credito ? $tipo_credito->id : NULL;
                        // $item2->contrapartida_id = $contrapartida ? $contrapartida->id : NULL;
                        $item2->conta_id = $subconta_movimentar ? $subconta_movimentar->conta_id : NULL;
                        $item2->updated_by = $user->id;
                        $item2->update();
                    }
                    
                }
                
                if($tipo_proveito->sigla == "S"){
                    // Retirar no conta 62
                    $subconta_movimentar = SubConta::where('numero', '62.1.1')->where('tipo', 'M')->first();
                    
                    if($subconta_movimentar){
                        $item2->debito = 0;
                        $item2->credito = $request->valor;
                        $item2->iva = 0;
                        $item2->descricao = $request->designacao;
                        $item2->subconta_id = $subconta_movimentar ? $subconta_movimentar->id : NULL;
                        $item2->tipo_movimento_id = $tipo_movimento_c ? $tipo_movimento_c->id : NULL;
                        $item2->documento_id = $documento ? $documento->id : NULL;
                        $item2->tipo_proveito_id = $tipo_proveito ? $tipo_proveito->id : NULL;
                        $item2->tipo_credito_id = NULL;
                        $item2->contrapartida_id = NULL;
                        // $item2->tipo_credito_id = $tipo_credito ? $tipo_credito->id : NULL;
                        // $item2->contrapartida_id = $contrapartida ? $contrapartida->id : NULL;
                        $item2->conta_id = $subconta_movimentar ? $subconta_movimentar->conta_id : NULL;
                        $item2->updated_by = $user->id;
                        $item2->update();
                    }
                    
                }
     
            }else if($tipo_movimento->sigla == "C"){
                
                $item1->debito = 0;
                $item1->credito = $request->valor;
                $item1->iva = 0;
                $item1->descricao = $request->designacao;
                $item1->subconta_id = $subconta->id;
                $item1->conta_id = $subconta->conta_id;
                $item1->tipo_movimento_id = $tipo_movimento ? $tipo_movimento->id : NULL;
                $item1->documento_id = $documento ? $documento->id : NULL;
                $item1->tipo_proveito_id = NULL;
                // $item1->tipo_proveito_id = $tipo_proveito ? $tipo_proveito->id : NULL;
                $item1->tipo_credito_id = $tipo_credito ? $tipo_credito->id : NULL;
                $item1->contrapartida_id = $contrapartida ? $contrapartida->id : NULL;
                $item1->user_id = $user->id;
                $item1->updated_by = $user->id;
                $item1->update();
                
                $contrapartida = Contrapartida::find($request->contrapartida_id);
                $subconta = SubConta::find($contrapartida->sub_conta_id);
                
                $tipo_movimento_d = TipoMovimento::where('sigla', 'D')->first();
                
                $item2->debito = $request->valor;
                $item2->credito = 0;
                $item2->iva = 0;
                $item2->descricao = $request->designacao;
                $item2->subconta_id = $subconta ? $subconta->id : NULL;
                $item2->tipo_movimento_id = $tipo_movimento_d ? $tipo_movimento_d->id : NULL;
                $item2->documento_id = $documento ? $documento->id : NULL;
                $item2->tipo_proveito_id =  NULL;
                $item1->tipo_credito_id = $tipo_credito ? $tipo_credito->id : NULL;
                $item2->contrapartida_id = $contrapartida ? $contrapartida->id : NULL;
                $item2->conta_id = $subconta ? $subconta->conta_id : NULL;
                $item2->user_id = $user->id;
                $item2->updated_by = $user->id;
                $item2->update();
                
            }
            
            // Confirmar a transação
            DB::commit();
            $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            $resultados = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
            return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
        
        } catch (QueryException $e) {
            // Se ocorrer um erro, reverter a transação
            DB::rollBack();
            // Lidar com o erro de alguma maneira, como exibir uma mensagem de erro
            // echo "Erro: " . $e->getMessage();
        }
        
        
    }    
    
    public function remover_fluxo_caixa(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        $item1 = MovimentoItem::findOrFail($id);
        $item2 = MovimentoItem::findOrFail($id + 1);
        
        $item1->delete();
        $item2->delete();
        
        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
        
    }

    public function show($id)
    {
        $data['movimento'] = Movimento::with(
            [
                'items.subconta', 
                'items.documento', 
                'items.contrapartida.sub_conta', 
                'items.tipo_proveito', 
                'items.tipo_credito', 
                'items.tipo_movimento', 
                'items.conta', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'
            ])
            
        ->where('origem', 'fluxocaixa')->findOrFail($id);
        
        return Inertia::render('FluxoCaixa/Show', $data);
    }
     
    public function edit(Request $request, $id)
    {
        // Retorna a lista de posts
        $user = User::with('empresa')->findOrFail(auth()->user()->id);
        $conta = Conta::where('numero', '45')->first();
        
        $data['subcontas'] = SubConta::where('tipo', 'M')->where('conta_id', $conta->id)->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->orderBy('numero','asc')->get();
        $data['documentos'] = Documento::select('id', 'designacao AS text')->get();
        $data['tipo_movimentos'] = TipoMovimento::select('id', 'designacao AS text')->orderBy('id', 'desc')->get();
        $data['tipo_creditos'] = TipoCredito::select('id', 'designacao AS text')->get();
        $data['tipo_proveitos'] = TipoProveito::select('id', 'designacao AS text')->get();
        
        $data['movimento'] = Movimento::findOrFail($id);
        
        $movimento = $data['movimento'];
        
        $data['saldo'] = MovimentoItem::with(['conta'])
        ->select('conta_id', 'empresa_id', 'movimento_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->where('conta_id', $conta->id)
        ->groupBy('conta_id', 'empresa_id', 'movimento_id')
        ->where('empresa_id', $this->empresaLogada())
        ->where('movimento_id', $movimento->id)
        ->first();
        
  
        $data['contrapartidas'] = Contrapartida::when($request->tipo_credito_id, function($query, $value){
            $query->where('tipo_credito_id' ,$value);
        })
        ->join('sub_contas', 'contrapartidas.sub_conta_id', '=', 'sub_contas.id')
        ->select('contrapartidas.id', 'contrapartidas.tipo_credito_id', DB::raw('CONCAT(sub_contas.numero, " - ", sub_contas.designacao) AS text'))->get();
        
        $data['item_movimentos'] = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->where('movimento_id',  $movimento->id)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            
        $data['resultados'] = MovimentoItem::with(['subconta.conta', 'empresa', "documento", "tipo_movimento"])->where('apresentar', 'S')->where('origem', 'fluxocaixa')->where('movimento_id',  $movimento->id)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        
        return Inertia::render('FluxoCaixa/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        dd($id);
    }

    public function imprimirPDF(Request $request)
    {
        $data['movimentos'] = Movimento::when($request->exercicio_id, function($query, $value){
            $query->where('exercicio_id', $value);
        })
        ->when($request->periodo_id, function($query, $value){
            $query->where('periodo_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('data_lancamento',  ">=" ,$value);
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('data_lancamento', "<=" ,$value);
        })
        ->with(['items', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'])
        ->where('origem', 'fluxocaixa')
        ->where('empresa_id', $this->empresaLogada())
        ->get();
        
        $data['requests'] = $request->all('data_inicio', 'data_final');
        
        $data['dados_empresa'] = Empresa::findOrFail($this->empresaLogada());
        
        $data['exercicio'] = Exercicio::find($request->exercicio_id);
        $data['periodo'] = Periodo::find($request->periodo_id);
        
        
        $pdf = PDF::loadView('pdf.contas.FluxoCaixa', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('FluxoCaixa.pdf');
    }

    public function imprimirDetalhePDF(Request $request, $id)
    {
        
        // $data['movimento'] = Movimento::with(['items', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'])
        // ->where('origem', 'fluxocaixa')
        // ->where('empresa_id', $this->empresaLogada())
        // ->findOrFail($request->movimento_id);
        
        // $data['dados_empresa'] = Empresa::findOrFail($this->empresaLogada());
        
        // $data['exercicio'] = Exercicio::find($request->exercicio_id);
        // $data['periodo'] = Periodo::find($request->periodo_id);
        
        // $pdf = PDF::loadView('pdf.contas.FluxoCaixa', $data)->setPaper('a4', 'landscape');
        // $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        // return $pdf->stream('FluxoCaixa.pdf');
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
