<?php

namespace App\Http\Controllers;
;

use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\SubConta;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;

class ExtratoContaController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
               
        $data['movimentos'] = MovimentoItem::whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->where('exercicio_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->periodo_id, function($query, $value){
                $query->where('periodo_id', $value);
            }); 
        })
        ->whereHas('subconta', function($query) use($request){
            $query->when($request->conta_id, function($query, $value){
                $query->where('conta_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->data_inicio, function($query, $value){
                $query->whereDate('data_lancamento',  ">=" ,$value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->data_final, function($query, $value){
                $query->whereDate('data_lancamento', "<=" ,$value);
            }); 
        })
        ->when($request->subconta_id, function($query, $value){
            $query->where('subconta_id', $value);
        })
        ->with(['subconta', 'movimento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')->get();
        
        $valores = [];
        $total_credito = 0;
        $total_debito = 0;
        
        foreach ($data['movimentos'] as $movimento) {

            $total_credito += $movimento->credito;
            $total_debito += $movimento->debito;
            
            $valores = [
                "total_credito" => $total_credito,
                "total_debito" => $total_debito,
            ];
        }
        
        $data['resultado'] = $valores;
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['subcontas'] = SubConta::with(['empresa', 'conta'])
        ->where('empresa_id', $this->empresaLogada())
        ->select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
        ->get();
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $this->empresaLogada())
        ->join('contas', "controle_conta_empresas.conta_id" , '=', 'contas.id')
        ->select('contas.id', 'contas.designacao As d', DB::raw('CONCAT(contas.numero, " - ", contas.designacao) AS text'))
        ->get();
           
        return Inertia::render('ExtratoContas/Index', $data);
    }

    public function create()
    {
       
    }

    public function store(Request $request)
    {

    }


    public function show($id)
    {

    }
  
    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function imprimirMovimento()
    {

    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirExtrato(Request $request){


        $users = User::with('empresa')->findOrFail(auth()->user()->id);
               
        $data['movimentos'] = MovimentoItem::whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->where('exercicio_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->periodo_id, function($query, $value){
                $query->where('periodo_id', $value);
            }); 
        })
        ->whereHas('subconta', function($query) use($request){
            $query->when($request->conta_id, function($query, $value){
                $query->where('conta_id', $value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->data_inicio, function($query, $value){
                $query->whereDate('data_lancamento',  ">=" ,$value);
            }); 
        })
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->data_final, function($query, $value){
                $query->whereDate('data_lancamento', "<=" ,$value);
            }); 
        })
        ->when($request->subconta_id, function($query, $value){
            $query->where('subconta_id', $value);
        })
        ->with(['subconta', 'movimento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')->get();
        
        $valores = [];
        $total_credito = 0;
        $total_debito = 0;
        
        foreach ($data['movimentos'] as $movimento) {

            $total_credito += $movimento->credito;
            $total_debito += $movimento->debito;
            
            $valores = [
                "total_debito" => $total_debito,
                "total_credito" => $total_credito,
            ];
        }
        
        $data['resultado'] = $valores;
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['subcontas'] = SubConta::with(['empresa', 'conta'])
        ->where('empresa_id', $this->empresaLogada())
        ->select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
        ->get();
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $this->empresaLogada())
        ->join('contas', "controle_conta_empresas.conta_id" , '=', 'contas.id')
        ->select('contas.id', 'contas.designacao As d', DB::raw('CONCAT(contas.numero, " - ", contas.designacao) AS text'))
        ->get();

        $data['dados_empresa'] = $this->dadosEmpresaLogada();

        $pdf = PDF::loadView('pdf.contas.ExtratoConta', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Extrato_conta.pdf');
    }

}
