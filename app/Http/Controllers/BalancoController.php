<?php

namespace App\Http\Controllers;
;

use App\Models\Exercicio;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalancoController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
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
