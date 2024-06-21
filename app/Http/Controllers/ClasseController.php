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
use PDF;

class ClasseController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
         
        $data['classes'] = ClasseEmpresa::with(['empresa', 'classe'])
        ->whereHas('classe', function($query) use($request){
            $query->when($request->classes_numero, function($query, $value){
                $query->orWhere('numero', $value);
            }); 
        })
        ->whereHas('classe', function($query) use($request){
            $query->when($request->classes_designacao, function($query, $value){
                $query->where('designacao', 'like', "%".$value."%");
            }); 
        })
        ->where('empresa_id', $this->empresaLogada())
        ->where('estado', '!=', 'inactivo')->get();
               
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
        
             
        $verificar = ClasseEmpresa::where('empresa_id', $this->empresaLogada())
            ->where('classe_id', $request->classe_id)
            ->first();
        
        if(!$verificar){
            ClasseEmpresa::create([
                'classe_id' => $request->classe_id,
                'estado' => $request->estado,
                'empresa_id' => $this->empresaLogada(),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'deleted_by' => auth()->user()->id,
            ]);        
        }else {
            return response()->json(['message' => "Esta Classe Já está cadastrada!"], 404);
        }
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
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
        try {

            $classe = ClasseEmpresa::findOrFail((int)$id);
            $classe->estado = 'inactivo';
            $classe->update();

            return response()->json(['message' => "O registo foi eliminado com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function imprimirClasses(){
        $data['classes_data'] = ClasseEmpresa::with(['empresa', 'classe'])->get();     
        
        $pdf = PDF::loadView('pdf.contas.Classes', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }
}
