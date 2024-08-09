<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\Exercicio;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\SubConta;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use PDF;
use TCPDF;
use App\Exports\MovimentoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class MovimentoController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
        $data['movimentos'] = Movimento::with(['exercicio', 'periodo' , 'criador', 'items'])
        ->when($request->exercicio_id, function($query, $value){
            $query->where('exercicio_id', $value);
        })
        ->whereHas('items', function($query) use($request){
            $query->when($request->sub_contas_id, function($query, $value){
                $query->where('subconta_id', $value);
            });
        })
        ->when($request->periodo_id, function($query, $value){
            $query->where('periodo_id', $value);
        })
        ->when($request->data_inicio, function($query, $value){
            $query->whereDate('created_at',  ">=" ,$value);
        })
        ->when($request->data_final, function($query, $value){
            $query->whereDate('created_at', "<=" ,$value);
        })
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')
        ->get();
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['subcontas'] = SubConta::with(['conta'])->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('empresa_id', $this->empresaLogada())->get();


        return Inertia::render('Movimentos/Index', $data);
    }

    public function create()
    {
        $data['diarios'] = Diario::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['tipo_documentos'] = TipoDocumento::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['contas'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

        $user = User::findOrFail(auth()->user()->id);

        $data['item_movimentos'] = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $data['resultados'] = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        $data['ultimo_movimento'] = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->where('empresa_id', $this->empresaLogada())->count();

        return Inertia::render('Movimentos/Create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            "exercicio_id" => "required",
            "periodo_id" => "required",
            "dia_id" => "required",
            "diario_id" => "required",
            "tipo_documento_id" => "required",
            "lancamento_atual" => "required",
        ],
        [
            "exercicio_id.required" => "Campo Obrigatório",
            "periodo_id.required" => "Campo Obrigatório",
            "dia_id.required" => "Campo Obrigatório",
            "diario_id.required" => "Campo Obrigatório",
            "tipo_documento_id.required" => "Campo Obrigatório",
            "lancamento_atual.required" => "Campo Obrigatório",
        ]);

        $user = User::findOrFail(auth()->user()->id);

        $codigo = time();

        $resultado = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito, SUM(iva) AS total_iva')->first();

        if($this->empresaLogada()){

            $create = Movimento::create([
                'hash' => $codigo,
                'debito' => $resultado->total_debito,
                'credito' => $resultado->total_credito,
                'iva' => $resultado->total_iva,
                'empresa_id' => $this->empresaLogada(),
                'descricao' => $request->descricao,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
                'dia_id' => $request->dia_id,
                'origem' => "movimento",
                'data_lancamento' => date("Y-m-d"),
                'lancamento_atual' => $request->lancamento_atual,
                'referencia_documento' => $request->referencia_documento,
                'data_movimento' => $request->data_movimento,
                'diario_id' => $request->diario_id,
                'tipo_documento_id' => $request->tipo_documento_id,
                'user_id' => $user->id,
                'created_by' => $user->id,
            ]);

            $items = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
            foreach ($items as $item) {
                $update = MovimentoItem::findOrFail($item->id);
                $update->hash = $codigo;
                $update->movimento_id = $create->id;
                $update->update();
            }
        }


        return redirect()->back();
        // return response()->json(['message' => "Dados salvos com sucesso!"])->setStatusCode(200);
        // return response()->json(['message' => "Dados salvos com sucesso!"], 200);

        // Salva um novo post no banco de dados
    }


    public function show($id)
    {
        $data['movimento'] = Movimento::with(["items.subconta.conta", "exercicio", "diario", "tipo_documento", "empresa", "criador"])->findOrFail($id);
        
        return Inertia::render('Movimentos/Show', $data);
    }


    public function carregar_lancamento_movimento()
    {
        $user = User::findOrFail(auth()->user()->id);

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();

        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);

    }

    public function adicionar_conta_movimento($id)
    {
        $user = User::findOrFail(auth()->user()->id);

        $subconta = SubConta::findOrFail($id);

        $hash = time();

        $verificar_movimento = MovimentoItem::where('movimento_id', NULL)->where('user_id', $user->id)->where('empresa_id', $this->empresaLogada())->first();

        if($verificar_movimento){

            MovimentoItem::create([
                'hash' => $verificar_movimento->hash,
                'debito' => 0,
                'credito' => 0,
                'iva' => 0,
                'descricao' => "",
                'origem' => "movimento",
                'empresa_id' => $this->empresaLogada(),
                'subconta_id' => $subconta->id,
                'conta_id' => $subconta->conta_id,
                'movimento_id' => NULL,
                'user_id' => $user->id,
                'created_by' => $user->id,
            ]);

        }else{
            MovimentoItem::create([
                'hash' => $hash,
                'debito' => 0,
                'credito' => 0,
                'iva' => 0,
                'descricao' => "",
                'origem' => "movimento",
                'empresa_id' => $this->empresaLogada(),
                'subconta_id' => $subconta->id,
                'conta_id' => $subconta->conta_id,
                'movimento_id' => NULL,
                'user_id' => $user->id,
                'created_by' => $user->id,
            ]);
        }

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();

        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);


    }

    public function remover_conta_movimento($id)
    {
        $user = User::findOrFail(auth()->user()->id);

        $movimento = MovimentoItem::findOrFail($id);
        $movimento->delete();

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);

    }

    public function inverter_valores_movimento($id)
    {
        $user = User::findOrFail(auth()->user()->id);

        $movimento = MovimentoItem::findOrFail($id);
        
        $debito = 0;
        $credito = 0;
        
        if($movimento->credito > 0){
            $debito = $movimento->credito;
            $credito = 0;
        }
        if($movimento->debito > 0){
            $debito = 0;
            $credito = $movimento->debito;
        }
        
        $movimento->debito = $debito;
        $movimento->credito = $credito;
        
        $movimento->update();

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);

    }

    public function alterar_debito_conta_movimento($id, $valor)
    {
        
        // $valor = number_format($valor / 100, 2, '.', '');
        
        $user = User::findOrFail(auth()->user()->id);

        $movimento = MovimentoItem::findOrFail($id);
        $movimento->debito = $valor;
        $movimento->update();


        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
    }

    public function alterar_credito_conta_movimento($id, $valor)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        // $valor = number_format($valor / 100, 2, '.', '');

        $movimento = MovimentoItem::findOrFail($id);
        $movimento->credito = $valor;
        $movimento->update();

        $session = Session::get('empresa_logada_mutue_contas_certas_2024');

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
    }

    public function alterar_iva_conta_movimento($id, $iva)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        // $iva = number_format($iva / 100, 2, '.', '');

        $movimento = MovimentoItem::findOrFail($id);
        $movimento->iva = $iva;
        $movimento->update();

        $session = Session::get('empresa_logada_mutue_contas_certas_2024');

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
    }

    public function alterar_descricao_conta_movimento($id, $descricao)
    {
        $user = User::findOrFail(auth()->user()->id);

        $movimento = MovimentoItem::findOrFail($id);
        $movimento->descricao = $descricao;
        $movimento->update();

        $session = Session::get('empresa_logada_mutue_contas_certas_2024');

        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
    }

    public function edit($id)
    {
        $data['movimento'] = Movimento::findOrFail($id);

        $data['diarios'] = Diario::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['tipo_documentos'] = TipoDocumento::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['contas'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

        $user = User::findOrFail(auth()->user()->id);

        $data['item_movimentos'] = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->get();
        $data['resultados'] = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $this->empresaLogada())->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();

        $data['ultimo_movimento'] = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->where('empresa_id', $this->empresaLogada())->count();

        return Inertia::render('Movimentos/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     "designacao" => "required",
        //     "numero" => "required",
        //     "estado" => "required",
        //     "diario_id" => "required",
        // ],
        // [
        //     "designacao.required" => "Campo Obrigatório",
        //     "numero.required" => "Campo Obrigatório",
        //     "estado.required" => "Campo Obrigatório",
        //     "diario_id.required" => "Campo Obrigatório",
        // ]);

        // // Atualiza um post específico no banco de dados
        // $diario = TipoDocumento::findOrFail($id);
        // $diario->designacao = $request->designacao;
        // $diario->diario_id = $request->diario_id;
        // $diario->numero = $request->numero;
        // $diario->estado = $request->estado;
        // $diario->update();

        // return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function imprimirMovimento(){

        $data['movimentos_data'] = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->where('empresa_id', $this->empresaLogada())->get();

        $pdf = PDF::loadView('pdf.contas.Movimentos', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }

    public function exportarExcel(){

        return Excel::download(new MovimentoExport(), 'balanco.xlsx');
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
