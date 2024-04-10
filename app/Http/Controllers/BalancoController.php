<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class BalancoController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $classes_activo_corrente = Classe::whereIn('id', ['4', '5'])->pluck('id');
        $classes_activo_nao_corrente = Classe::whereIn('id', ['2', '3'])->pluck('id');
        
        $classes_passivos_corrente = Classe::whereIn('id', ['4'])->pluck('id');
        $classes_passivos_nao_corrente = Classe::whereIn('id', ['4'])->pluck('id');
                
        $contas_activos_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_corrente)->pluck('conta_id');
        $contas_activos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_nao_corrente)->pluck('conta_id');
        
        $contas_passivos_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_corrente)->pluck('conta_id');
        $contas_passivos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_nao_corrente)->pluck('conta_id');
        
        
        $data['conta_do_activos_corrente'] = MovimentoItem::with(['conta', 'movimento'])->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),
        )->whereIn('conta_id', $contas_activos_corrente)
        ->groupBy('conta_id')
        ->get();
        
        $data['conta_do_activos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $contas_activos_nao_corrente)
        ->groupBy('conta_id')
        ->get();
        
        
        $data['conta_do_passivos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $contas_passivos_corrente)
        ->groupBy('conta_id')
        ->get();
        
        $data['conta_do_passivos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $contas_passivos_nao_corrente)
        ->groupBy('conta_id')
        ->get();
        
        
                
        $capital_proprio = Conta::whereIn('numero', ['51', '55', '81', '88'])->pluck('id');
        $data['capital_proprio'] = MovimentoItem::with(['conta', 'movimento'])
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $capital_proprio)
        ->groupBy('conta_id')
        ->get();
        
        // dd($data['capital_proprio']);


        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        
        return Inertia::render('Balancos/Index', $data);
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

    public function imprimirBalanco(Request $request)
    {
        
        $classes_activo_corrente = Classe::whereIn('id', ['4', '5'])->pluck('id');
        $classes_activo_nao_corrente = Classe::whereIn('id', ['2', '3'])->pluck('id');
        
        $classes_passivos_corrente = Classe::whereIn('id', ['4'])->pluck('id');
        $classes_passivos_nao_corrente = Classe::whereIn('id', ['4'])->pluck('id');
                
        $contas_activos_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_corrente)->pluck('conta_id');
        $contas_activos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_nao_corrente)->pluck('conta_id');
        
        $contas_passivos_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_corrente)->pluck('conta_id');
        $contas_passivos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_nao_corrente)->pluck('conta_id');
                
        
        $data['conta_do_activos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->orWhere('exercicio_id', $value);
            }); 
        })
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
        ->whereIn('conta_id', $contas_activos_corrente)
        ->groupBy('conta_id')
        ->get();
        
        $data['conta_do_activos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->orWhere('exercicio_id', $value);
            }); 
        })
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $contas_activos_nao_corrente)
        ->groupBy('conta_id')
        ->get();
        
        
        $data['conta_do_passivos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->orWhere('exercicio_id', $value);
            }); 
        })
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->orWhere('exercicio_id', $value);
            }); 
        })
        ->whereIn('conta_id', $contas_passivos_corrente)
        ->groupBy('conta_id')
        ->get();
        
        $data['conta_do_passivos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
        ->whereHas('movimento', function($query) use($request){
            $query->when($request->exercicio_id, function($query, $value){
                $query->orWhere('exercicio_id', $value);
            }); 
        })
        ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'), )
        ->whereIn('conta_id', $contas_passivos_nao_corrente)
        ->groupBy('conta_id')
        ->get();


        $data['exercicio'] = Exercicio::find($request->exercicio_id);
        $data['requests'] = $request->all('exercicio_id', 'data_inicio', 'data_final');
        $data['dados_empresa'] = $this->dadosEmpresaLogada();
        
        $pdf = PDF::loadView('pdf.contas.Balanco', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Balanco.pdf');
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
