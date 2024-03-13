<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\SubConta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubContaController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['subcontas'] = SubConta::with(['empresa', 'conta'])->where('empresa_id', $users->empresa_id)->paginate(7);
               
        return Inertia::render('SubContas/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $users->empresa_id)->join('contas', 'controle_conta_empresas.conta_id' , '=', 'contas.id')->select('contas.id', 'contas.designacao AS text')
        ->get();
       
        return Inertia::render('SubContas/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $request->validate([
            "conta_id" => "required",
            "designacao" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "conta_id.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        $classes =  SubConta::create([
            'conta_id' => $request->conta_id,
            'designacao' => $request->designacao,
            'numero' => $request->numero,
            'estado' => $request->estado,
            'empresa_id' => auth()->user()->empresa_id,
        ]);
        
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
        $data['subconta'] = SubConta::findOrFail($id);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $users->empresa_id)
        ->join('contas', 'controle_conta_empresas.conta_id' , '=', 'contas.id')
        ->select('contas.id', 'contas.designacao AS text')
        ->get();
       
        return Inertia::render('SubContas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "conta_id" => "required",
            "designacao" => "required",
            "numero" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
        ]);
        


        // Atualiza um post específico no banco de dados
        $classe = SubConta::findOrFail($id);
        $classe->conta_id = $request->conta_id;
        $classe->designacao = $request->designacao;
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
