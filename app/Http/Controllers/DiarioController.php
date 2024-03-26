<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\Exercicio;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class DiarioController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->
        $data['exercicios'] = Exercicio::get();
        
        $data['diarios'] = Diario::when($request->input_busca_diarios, function($query, $value){
            $query->where('designacao', 'like', "%".$value."%");
            $query->orWhere('numero', $value);
        })
        ->with(['empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();
               
        return Inertia::render('Diarios/Index', $data);
    }

    public function create()
    {
        $data['diario'] = [];
       
        return Inertia::render('Diarios/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            "designacao" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
            
        if($this->empresaLogada()){
            
            Diario::create([
                'designacao' => $request->designacao,
                'numero' => $request->numero,
                'estado' => $request->estado,
                'created_by' => auth()->user()->id,
                'empresa_id' => $this->empresaLogada(),
            ]);
        
        }
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }

    public function get_diario($id)
    {
        $diario = Diario::findOrFail($id);
        $tipos_documentos = TipoDocumento::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->where('diario_id', $diario->id)->get();
        
        return response()->json(
            [
                'diario' => $diario, 
                'tipos_documentos' => $tipos_documentos, 
            ]
        , 200);
    }
    

    public function show($id)
    {
    
        $diario = Diario::findOrFail($id);
        
        $estado = "";
        
        if($diario->estado == "activo"){
            $estado = "desactivo";
        }
        if($diario->estado == "desactivo"){
            $estado = "activo";
        }
        
        $diario->estado = $estado;
        $diario->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['diario'] = Diario::findOrFail($id);

        return Inertia::render('Diarios/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "designacao" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $diario = Diario::findOrFail($id);
        $diario->designacao = $request->designacao;
        $diario->numero = $request->numero;
        $diario->estado = $request->estado;
        $diario->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirDiario(){ 
        // $data['diario_data'] = Diario::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
        $data['diarios'] = Diario::with(['empresa'])->get();
        
        $pdf = PDF::loadView('pdf.contas.Diario', $data)->setPaper('a3', 'landscape');
        return $pdf->stream('Contas.pdf');
    }
}
