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
use Inertia\Inertia;
use PDF;
use App\Exports\MovimentoExport;
use App\Models\CentroCusto;
use App\Models\CentroDeCusto;
use App\Models\Conta;
use App\Models\Documento;
use App\Models\Factura;
use Maatwebsite\Excel\Facades\Excel;

class FacturaController extends Controller
{
    use Config;
    //
    public function home(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
      
        $data['facturas'] = Factura::with(['cliente', 'fornecedor' , 'exercicio', 'centro_de_custo', 'periodo', 'diario', 'tipo_documento', 'empresa', 'user'])
        // ->when($request->exercicio_id, function($query, $value){
        //     $query->where('exercicio_id', $value);
        // })
        // ->when($request->periodo_id, function($query, $value){
        //     $query->where('periodo_id', $value);
        // })
        // ->when($request->data_inicio, function($query, $value){
        //     $query->whereDate('created_at',  ">=" ,$value);
        // })
        // ->when($request->data_final, function($query, $value){
        //     $query->whereDate('created_at', "<=" ,$value);
        // })
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')
        ->get();
        
        $data['facturas_em_atraso'] = Factura::whereIn('status', ['Em atraso'])->count();
        $data['facturas_pendentes'] = Factura::whereIn('status', ['Pendente'])->count();
        $data['facturas_pagas'] = Factura::whereIn('status', ['Pago'])->count();
                
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['subcontas'] = SubConta::with(['conta'])->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('empresa_id', $this->empresaLogada())->get();


        return Inertia::render('Facturas/Home', $data);
    }
    
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
      
        $data['facturas'] = Factura::with(['cliente', 'fornecedor' , 'exercicio', 'centro_de_custo', 'periodo', 'diario', 'tipo_documento', 'empresa', 'user'])
        // ->when($request->exercicio_id, function($query, $value){
        //     $query->where('exercicio_id', $value);
        // })
        // ->when($request->periodo_id, function($query, $value){
        //     $query->where('periodo_id', $value);
        // })
        // ->when($request->data_inicio, function($query, $value){
        //     $query->whereDate('created_at',  ">=" ,$value);
        // })
        // ->when($request->data_final, function($query, $value){
        //     $query->whereDate('created_at', "<=" ,$value);
        // })
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')
        ->get();
        
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['subcontas'] = SubConta::with(['conta'])->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('empresa_id', $this->empresaLogada())->get();


        return Inertia::render('Facturas/Index', $data);
    }

    public function create()
    {
        $data['documentos'] = Documento::select('id', DB::raw('CONCAT(sigla, " - ", designacao) AS text'))->get();
        
        $data['centro_custos'] = CentroDeCusto::select('id', 'designacao AS text')->get();
        
        $clientes = Conta::whereIn('numero', ['31'])->pluck('id');
        $fornecedores = Conta::whereIn('numero', ['32'])->pluck('id');
        
        $data['subcontas_clientes'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('tipo', 'M')->whereIn('conta_id', $clientes)->get();
        $data['subcontas_fornecedores'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('tipo', 'M')->whereIn('conta_id', $fornecedores)->get();
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();

        return Inertia::render('Facturas/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            "exercicio_id" => "required",
            "periodo_id" => "required",
            "data_movimento" => "required",
            "referencia_documento" => "required",
            "tipo_documento_id" => "required",
            "cliente_id" => "required",
            "fornecedor_id" => "required",
            "valor_total" => "required",
            "status" => "required",
            "centro_custo_id" => "required",
        ],
        [
            "exercicio_id.required" => "Campo Obrigatório",
            "periodo_id.required" => "Campo Obrigatório",
            "data_movimento.required" => "Campo Obrigatório",
            "referencia_documento.required" => "Campo Obrigatório",
            "tipo_documento_id.required" => "Campo Obrigatório",
            "cliente_id.required" => "Campo Obrigatório",
            "fornecedor_id.required" => "Campo Obrigatório",
            "valor_total.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "centro_custo_id.required" => "Campo Obrigatório",
        ]);

        $user = User::findOrFail(auth()->user()->id);
        
        $create = Factura::create([
            'valor_total' => $request->valor_total,
            'fornecedor_id' => $request->fornecedor_id,
            'tipo_documento_id' => $request->tipo_documento_id,
            'referencia_factura' => $request->referencia_documento,
            'centro_custo_id' => $request->centro_custo_id,
            'cliente_id' => $request->cliente_id,
            'data_vencimento' => $request->data_vencimento,
            'data_factura' => $request->data_movimento,
            'status' => $request->status,
            'user_id' => $user->id,
            'descricao' => $request->descricao,
            'empresa_id' => $this->empresaLogada(),
            'exercicio_id' => $request->exercicio_id,
            'periodo_id' => $request->periodo_id,
        
        ]);

        return redirect()->back();
    }

    public function show($id)
    {
    
        $data['movimento'] = Movimento::with(["items.subconta.conta", "exercicio", "diario", "tipo_documento", "empresa", "criador"])->findOrFail($id);

        return Inertia::render('Movimentos/Show', $data);
    }

    public function edit($id)
    {
        $data['factura'] = Factura::findOrFail($id);
        
        $data['documentos'] = Documento::select('id', DB::raw('CONCAT(sigla, " - ", designacao) AS text'))->get();
        
        $data['centro_custos'] = CentroDeCusto::select('id', 'designacao AS text')->get();
        
        $clientes = Conta::whereIn('numero', ['31'])->pluck('id');
        $fornecedores = Conta::whereIn('numero', ['32'])->pluck('id');
        
        $data['subcontas_clientes'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('tipo', 'M')->whereIn('conta_id', $clientes)->get();
        $data['subcontas_fornecedores'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('tipo', 'M')->whereIn('conta_id', $fornecedores)->get();
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        
     
        return Inertia::render('Facturas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "exercicio_id" => "required",
            "periodo_id" => "required",
            "data_movimento" => "required",
            "referencia_documento" => "required",
            "tipo_documento_id" => "required",
            "cliente_id" => "required",
            "fornecedor_id" => "required",
            "valor_total" => "required",
            "status" => "required",
            "centro_custo_id" => "required",
        ],
        [
            "exercicio_id.required" => "Campo Obrigatório",
            "periodo_id.required" => "Campo Obrigatório",
            "data_movimento.required" => "Campo Obrigatório",
            "referencia_documento.required" => "Campo Obrigatório",
            "tipo_documento_id.required" => "Campo Obrigatório",
            "cliente_id.required" => "Campo Obrigatório",
            "fornecedor_id.required" => "Campo Obrigatório",
            "valor_total.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
            "centro_custo_id.required" => "Campo Obrigatório",
        ]);

        $user = User::findOrFail(auth()->user()->id);
        
        $factura = Factura::findOrFail($id);
        
        try {

            $factura->valor_total = $request->valor_total;
            $factura->fornecedor_id = $request->fornecedor_id;
            $factura->tipo_documento_id = $request->tipo_documento_id;
            $factura->referencia_factura = $request->referencia_documento;
            $factura->centro_custo_id = $request->centro_custo_id;
            $factura->cliente_id = $request->cliente_id;
            $factura->data_vencimento = $request->data_vencimento;
            $factura->data_factura = $request->data_movimento;
            $factura->status = $request->status;
            $factura->descricao = $request->descricao;
            $factura->exercicio_id = $request->exercicio_id;
            $factura->periodo_id = $request->periodo_id;
            $factura->update();
            
            return response()->json(['message' => 'Registo actualizado com sucesso!'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Não foi possível actualizar o registo!'], 201);
        }
        

        return redirect()->back();
    
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
