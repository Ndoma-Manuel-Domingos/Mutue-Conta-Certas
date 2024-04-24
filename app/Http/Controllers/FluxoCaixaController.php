<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        
        return Inertia::render('FluxoCaixa/Index', $data);
    }

    public function create()
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
            
        $data['fluxos_caixas'] = [];
        
        return Inertia::render('FluxoCaixa/Create', $data);
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
