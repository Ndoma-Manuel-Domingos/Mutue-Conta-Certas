<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContaController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['contas'] = Conta::with(['empresa', 'classe'])->where('empresa_id', $users->empresa_id)->paginate(7);
               
        return Inertia::render('Contas/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        
        $data['classes'] = Classe::select('id', 'nome AS text')->get();
       
        return Inertia::render('Contas/Create', $data);
    }

    public function store(Request $request)
    {
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $request->validate([
            "classe_id" => "required",
            "nome" => "required",
            "status" => "required",
        ], 
        [
            "classe_id.required" => "Campo Obrigatório",
            "nome.required" => "Campo Obrigatório",
            "status.required" => "Campo Obrigatório",
        ]);
        
        $classes =  Conta::create([
            'classe_id' => $request->classe_id,
            'nome' => $request->nome,
            'codigo' => $request->codigo,
            'status' => $request->status,
            'empresa_id' => auth()->user()->empresa_id,
        ]);
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }

    public function show($id)
    {
        // Exibe um post específico
    }

    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['conta'] = Conta::findOrFail($id);
        
        $data['classes'] = Classe::select('id', 'nome AS text')->get();
       
        return Inertia::render('Contas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "status" => "required",
            "classe_id" => "required",
            "nome" => "required",
            "codigo" => "required",
        ], 
        [
            "status.required" => "Campo Obrigatório",
            "classe_id.required" => "Campo Obrigatório",
            "nome.required" => "Campo Obrigatório",
            "codigo.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $classe = Conta::findOrFail($id);
        $classe->classe_id = $request->classe_id;
        $classe->nome = $request->nome;
        $classe->codigo = $request->codigo;
        $classe->status = $request->status;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
