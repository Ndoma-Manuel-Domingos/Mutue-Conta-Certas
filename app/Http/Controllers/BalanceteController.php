<?php

namespace App\Http\Controllers;
;

use App\Models\Exercicio;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\SubConta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class BalanceteController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
        // $data['movimentos'] = MovimentoItem::join('sub_contas', 'movimento_items.subconta_id', '=', 'sub_contas.id')
        // ->join('contas', 'sub_contas.conta_id', '=', 'contas.id')
        // ->select('contas.designacao', 'sub_contas.conta_id', \DB::raw('SUM(credito) as total_credito'))
        // ->with(['subconta', 'movimento', 'criador'])
        // ->where('movimento_items.empresa_id', $this->empresaLogada())
        // ->groupBy('sub_contas.conta_id')
        // ->get();
        
        // $data['movimentos'] = MovimentoItem::join('sub_contas', 'movimento_items.subconta_id', '=', 'sub_contas.id')
        // ->join('contas', 'sub_contas.conta_id', '=', 'contas.id')
        // ->select('sub_contas.conta_id', \DB::raw('SUM(movimento_items.credito) as total_credito, SUM(movimento_items.debito) as total_debito'))
        // ->with(['subconta', 'movimento', 'criador'])
        // ->where('movimento_items.empresa_id', $this->empresaLogada())
        // ->groupBy('sub_contas.conta_id')
        // ->first();
            
        // dd($data['movimentos']);
            
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
        ->when($request->conta_id, function($query, $value){
            $query->where('subconta_id', $value);
        })
        ->with(['subconta', 'movimento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')->get();
        
        $valores = [];
        
        $movimento_debito = 0;
        $movimento_credito = 0;
        $total_credito = 0;
        $total_debito = 0;
        
        foreach ($data['movimentos'] as $movimento) {
            
            if($movimento->debito > $movimento->credito){
                $movimento_debito += ($movimento->debito - $movimento->credito);
            }
            
            if($movimento->credito > $movimento->debito){
                $movimento_credito += ($movimento->credito - $movimento->debito);
            }
            
            $total_credito += $movimento->credito;
            $total_debito += $movimento->debito;
            
            $valores = [
                "total_movimento_credito" => $movimento_credito,
                "total_movimento_debito" => $movimento_debito,
                "total_credito" => $total_credito,
                "total_debito" => $total_debito,
            ];
        }
        
        $data['resultado'] = $valores;
        
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        
        $data['contas'] = SubConta::where('empresa_id', $this->empresaLogada())
        ->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
        ->get();
        
        return Inertia::render('Balancetes/Index', $data);
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

    public function imprimirBalancete(Request $request){
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
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
        ->when($request->conta_id, function($query, $value){
            $query->where('subconta_id', $value);
        })
        ->with(['subconta', 'movimento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'desc')->get();
        
        $valores = [];
        
        $movimento_debito = 0;
        $movimento_credito = 0;
        $total_credito = 0;
        $total_debito = 0;
        
        foreach ($data['movimentos'] as $movimento) {
            
            if($movimento->debito > $movimento->credito){
                $movimento_debito += ($movimento->debito - $movimento->credito);
            }
            
            if($movimento->credito > $movimento->debito){
                $movimento_credito += ($movimento->credito - $movimento->debito);
            }
            
            $total_credito += $movimento->credito;
            $total_debito += $movimento->debito;
            
            $valores = [
                "total_movimento_debito" => $movimento_debito,
                "total_movimento_credito" => $movimento_credito,
                "total_debito" => $total_debito,
                "total_credito" => $total_credito,
            ];
        }
        
        $data['resultado'] = $valores;
        
        
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        
        $data['contas'] = SubConta::where('empresa_id', $this->empresaLogada())
        ->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
        ->get();
           
        $data['dados_empresa'] = $this->dadosEmpresaLogada();
        
        $pdf = PDF::loadView('pdf.contas.Balancete', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Balancete.pdf');
    }
}
