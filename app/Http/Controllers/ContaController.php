<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\ContaEmpresa;
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
        
        $data['contas'] = ContaEmpresa::with(['empresa', 'conta', 'classe'])->where('empresa_id', $users->empresa_id)->paginate(7);
               
        return Inertia::render('Contas/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        $data['classes'] = Classe::select('id', 'designacao AS text')->get();
        $data['contas'] = Conta::select('id', 'designacao AS text')->get();
       
        return Inertia::render('Contas/Create', $data);
    }

    public function store(Request $request)
    {

        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $request->validate([
            "classe_id" => "required",
            "conta_id" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "classe_id.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        $classes =  ContaEmpresa::create([
            'classe_id' => $request->classe_id,
            'conta_id' => $request->conta_id,
            'numero' => $request->numero,
            'estado' => $request->estado,
            'created_by' => auth()->user()->id,
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
        $data['conta'] = ContaEmpresa::findOrFail($id);
        
        $data['classes'] = Classe::select('id', 'designacao AS text')->get();
        $data['contas'] = Conta::select('id', 'designacao AS text')->get();
       
        return Inertia::render('Contas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "classe_id" => "required",
            "conta_id" => "required",
            "numero" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "classe_id.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $classe = ContaEmpresa::findOrFail($id);
        $classe->classe_id = $request->classe_id;
        $classe->conta_id = $request->conta_id;
        $classe->numero = $request->numero;
        $classe->estado = $request->estado;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
