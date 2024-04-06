<?php

namespace App\Http\Controllers;
;

use App\Models\Classe;
use App\Models\ClasseEmpresa;
use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
        
        // dd($data['conta_do_activos'], $data['conta_do_passivos']);
        
        // , 'sub_contas_empresa.items_movimentos' => function ($query) {
        //     $query->select(
        //         'subconta_id',
        //         DB::raw('sum(debito) as total_debito'),
        //         DB::raw('sum(credito) as total_credito'),
        //     )->groupBy('subconta_id');
        // }
        
        // dd($data['conta_do_activo']);
        
        // where('empresa_id', $users->empresa_id)->
        
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

    public function imprimirMovimento()
    {

    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
