<?php

namespace App\Http\Controllers;

use App\Models\ClasseEmpresa;
use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanoGeralContaController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['plano'] = ClasseEmpresa::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
               
        // dd($data['plano']);
               
        return Inertia::render('PlanoGeralConta/Index', $data);
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

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
