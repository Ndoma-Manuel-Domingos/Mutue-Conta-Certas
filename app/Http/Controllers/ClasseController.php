<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\ClasseEmpresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ClasseController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['classes'] = ClasseEmpresa::with(['empresa', 'classe'])->paginate(7);
               
        return Inertia::render('Classes/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        
        $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('Classes/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $validator = Validator::make($request->all(), [
            "classe_id" => "required",
            "estado" => "required",
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $classes =  ClasseEmpresa::create([
            'classe_id' => $request->classe_id,
            'estado' => $request->estado,
            'empresa_id' => auth()->user()->empresa_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'deleted_by' => auth()->user()->id,
        ]);
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }

    public function show($id)
    {
    
        $empresa = ClasseEmpresa::findOrFail($id);
        
        $estado = "";
        
        if($empresa->estado == "activo"){
            $estado = "desactivo";
        }
        if($empresa->estado == "desactivo"){
            $estado = "activo";
        }
        
        $empresa->estado = $estado;
        $empresa->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['classe'] = ClasseEmpresa::findOrFail($id);
        
        $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('Classes/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "classe_id" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "classe_id.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $classe = ClasseEmpresa::findOrFail($id);
        $classe->classe_id = $request->classe_id;
        $classe->estado = $request->estado;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
