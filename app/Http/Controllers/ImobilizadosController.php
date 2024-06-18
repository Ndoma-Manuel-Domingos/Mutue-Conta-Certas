<?php

namespace App\Http\Controllers;

use App\Models\Amortizacao;
use App\Models\ClassificacaoImobilizado;
use App\Models\Conta;
use App\Models\Diario;
use App\Models\Exercicio;
use App\Models\Imobilizado;
use App\Models\Periodo;
use App\Models\SubConta;
use App\Models\TabelaAmortizacao;
use App\Models\TabelaAmortizacaoItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class ImobilizadosController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['imobilizados'] = Imobilizado::with(['amortizacao','amortizacao_item','classificacao','exercicio','periodo','empresa'])
            ->where('empresa_id', $this->empresaLogada())
            ->get();
                           
        return Inertia::render('Imobilizados/Index', $data);
    }
    
    //
    public function mapa_amortizacoes(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        // $data['registros'] = ContaEmpresa::with(['classe'])->with(['conta.items_movimentos' => function ($query) {
        //     $query->select(
        //         'conta_id',
        //         DB::raw('sum(debito) as TotalDebito'),
        //         DB::raw('sum(credito) as TotalCredito'),
        //     )->groupBy('conta_id');
        // }, 'sub_contas_empresa.items_movimentos' => function ($query) {
        //     $query->select(
        //         'subconta_id',
        //         DB::raw('sum(debito) as total_debito'),
        //         DB::raw('sum(credito) as total_credito'),
        //     )->groupBy('subconta_id');
        // }])
        // ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
        //     $query->when($request->data_inicio, function($query, $value){
        //         $query->whereDate('data_lancamento',  ">=" ,$value);
        //     }); 
        // })
        // ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
        //     $query->when($request->data_final, function($query, $value){
        //         $query->whereDate('data_lancamento', "<=" ,$value);
        //     }); 
        // })
        // ->paginate(100);

        $data['imobilizados'] = Imobilizado::with(['amortizacao','amortizacao_item','classificacao','exercicio','periodo','empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();
            
               
        return Inertia::render('Imobilizados/MapaAmortizacao', $data);
    }
    
    //
    public function imprimir_mapa_amortizacoes(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['imobilizados'] = Imobilizado::with(['amortizacao','amortizacao_item','classificacao','exercicio','periodo','empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();
        
        $data['dados_empresa'] = $this->dadosEmpresaLogada();

        $pdf = PDF::loadView('pdf.contas.MapaAmortizacao', $data)
            ->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('MapaAmortizacao.pdf');
               
    }
    //
    public function imprimirFichaImobilizado(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['dados_empresa'] = $this->dadosEmpresaLogada();
                
        $data['imobilizado'] = Imobilizado::with(['amortizacao','amortizacao_item','classificacao','exercicio','periodo','empresa'])
            ->where('empresa_id', $this->empresaLogada())
            ->findOrFail($request->id);

        $pdf = PDF::loadView('pdf.contas.FichaImobilizado', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('FichaImobilizado.pdf');
               
    }

    public function create(Request $request)
    {
        
        $data['amortizacoes'] = TabelaAmortizacao::select('id', DB::raw('CONCAT(ordem, " - ", designacao) AS text'))->get();
        $data['exercicios'] = Exercicio::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        $data['periodos'] = Periodo::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();

        $conta = Conta::whereIn('numero', ['11', '12'])->pluck('id');
        $data['subcontas'] = SubConta::where('tipo', 'M')->whereIn('conta_id', $conta)->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->orderBy('numero','asc')->get();
        
        $data['amortizacoesItem'] = TabelaAmortizacaoItem::with(['amortizacao'])->when($request->amortizacao_id, function($query, $value){
            $query->where('amortizacao_id', $value);
        })
        ->select('id', DB::raw('CONCAT(designacao, " - ", taxa , "% - ", vida_util) AS text'))
        ->get();
        
        $data['items'] = TabelaAmortizacaoItem::when($request->amortizacao_item_id, function($query, $value){
            $query->where('id', $value);
        })
        ->first();
                       
        $data['categorias'] = ClassificacaoImobilizado::with(['empresa'])
            ->where('empresa_id', $this->empresaLogada())
            ->select('id', DB::raw('designacao AS text'))
            ->get();
            
        return Inertia::render('Imobilizados/Create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            "designacao" => "required",
            "exercicio_id" => "required",
            "periodo_id" => "required",
            "amortizacao_id" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "exercicio_id.required" => "Campo Obrigatório",
            "periodo_id.required" => "Campo Obrigatório",
            "amortizacao_id.required" => "Campo Obrigatório",
        ]);
        
        $classificacao = ClassificacaoImobilizado::find($request->classificacao_id);

        
        foreach ($request->quantidade as $qtds) {
              
            $digits = range(0, 9);
            shuffle($digits);
            $uniqueDigits = array_slice($digits, 0, 4);
            $uniqueNumber = (int) implode('', $uniqueDigits);
            
            $sigla = $classificacao->sigla . "-{$uniqueNumber}";
            
            $create = Imobilizado::create([
                'status' => $request->estado,
                'designacao' => $request->designacao,
                'conta' => $request->conta,
                'origem_id' => $request->origem_id,
                'classificacao_id' => $classificacao->id,
                'sigla' => $sigla,
                'exercicio_id' => $request->exercicio_id,
                'empresa_id' => $this->empresaLogada(),
                'periodo_id' => $request->periodo_id,
                'amortizacao_id' => $request->amortizacao_id,
                'amortizacao_item_id' => $request->amortizacao_item_id,
                'valor_aquisicao' => $request->valor_aquisicao,
                'quantidade' => 1,
                'data_aquisicao' => $request->data_aquisicao,
                'data_utilizacao' => $request->data_utilizacao,
                'created_by' => auth()->user()->id,
            ]);
        }
        
        
        // return redirect("/imprimir-ficha-imobilizado?id={$create->id}");
        
        return response()->json(['message' => "Dados salvos com sucesso!", 'data' => $create], 200);
    
        // Salva um novo post no banco de dados
    }

    public function show($id)
    {
        $data['imobilizado'] = Imobilizado::with(['amortizacao','amortizacao_item','classificacao','exercicio','periodo','empresa'])->findOrFail($id);
       
        return Inertia::render('Imobilizados/Show', $data);
    }
    
    public function edit(Request $request, $id)
    {
            
        $data['amortizacoes'] = TabelaAmortizacao::select('id', DB::raw('CONCAT(ordem, " - ", designacao) AS text'))->get();
        $data['exercicios'] = Exercicio::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();
        $data['periodos'] = Periodo::select('id', 'designacao AS text')->where('empresa_id', $this->empresaLogada())->get();

        $data['amortizacoesItem'] = TabelaAmortizacaoItem::with(['amortizacao'])->when($request->amortizacao_id, function($query, $value){
            $query->where('amortizacao_id', $value);
        })
        ->select('id', DB::raw('CONCAT(designacao, " - ", taxa , "% - ", vida_util) AS text'))
        ->get();
        
        $data['items'] = TabelaAmortizacaoItem::when($request->amortizacao_item_id, function($query, $value){
            $query->where('id', $value);
        })
        ->first();
            
        $data['categorias'] = ClassificacaoImobilizado::with(['empresa'])
            ->where('empresa_id', $this->empresaLogada())
            ->select('id', DB::raw('designacao AS text'))
            ->get();
            
        // Exibe o formulário para editar um post
        $data['imobilizado'] = Imobilizado::findOrFail($id);

        return Inertia::render('Imobilizados/Edit', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "designacao" => "required",
            "conta" => "required",
            "estado" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "conta.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
       
        $imobilizado = Imobilizado::findOrFail($id);
       
        $classificacao = ClassificacaoImobilizado::find($request->classificacao_id);
        
        if($imobilizado->classificacao_id == $classificacao->id){
            $sigla = $imobilizado->sigla;
        }else{
                
            $digits = range(0, 9);
            shuffle($digits);
            $uniqueDigits = array_slice($digits, 0, 4);
            $uniqueNumber = (int) implode('', $uniqueDigits);
            
            $sigla = $classificacao->sigla . "_{$uniqueNumber}";
        }
        
        // Atualiza um post específico no banco de dados

        $imobilizado->status = $request->estado;
        $imobilizado->designacao = $request->designacao;
        $imobilizado->conta = $request->conta;
        $imobilizado->origem_id = $request->origem_id;
        $imobilizado->classificacao_id = $classificacao->id;
        $imobilizado->sigla = $sigla;
        $imobilizado->exercicio_id = $request->exercicio_id;
        $imobilizado->periodo_id = $request->periodo_id;
        // $imobilizado->amortizacao_id = $request->amortizacao_id;
        // $imobilizado->amortizacao_item_id = $request->amortizacao_item_id;
        $imobilizado->valor_aquisicao = $request->valor_aquisicao;
        $imobilizado->quantidade = $request->quantidade;
        $imobilizado->data_aquisicao = $request->data_aquisicao;
        $imobilizado->data_utilizacao = $request->data_utilizacao;
        
        $imobilizado->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirDiario(){ 
        // $data['diario_data'] = Diario::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
        $data['imobilizados'] = Diario::with(['empresa'])->get();
        
        $pdf = PDF::loadView('pdf.contas.Diario', $data)->setPaper('a3', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }
}
