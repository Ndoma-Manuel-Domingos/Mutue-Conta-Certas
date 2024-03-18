<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class TipoDocumentoController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->

        $data['tipos_documentos'] = TipoDocumento::with(['empresa', 'diario'])->paginate(10);
               
        return Inertia::render('TipoDocumentos/Index', $data);
    }

    public function create()
    {
        $data['diarios'] = Diario::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('TipoDocumentos/Create', $data);
    }

    public function store(Request $request)
    {
 
        $request->validate([
            "designacao" => "required",
            "diario_id" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "diario_id.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
    
        $empresa_sessao_global = Session::get('empresa_logada_mutue_contas_certas_2024');
                
        if($empresa_sessao_global){
            
            TipoDocumento::create([
                'designacao' => $request->designacao,
                'diario_id' => $request->diario_id,
                'numero' => $request->numero,
                'estado' => $request->estado,
                'created_by' => auth()->user()->id,
                'empresa_id' => $empresa_sessao_global['id'],
            ]);
        
        }
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }


    public function show($id)
    {
    
        $diario = TipoDocumento::findOrFail($id);
        
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
        $data['tipo_documento'] = TipoDocumento::findOrFail($id);
        
        $data['diarios'] = Diario::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

        return Inertia::render('TipoDocumentos/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "designacao" => "required",
            "numero" => "required",
            "estado" => "required",
            "diario_id" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
            "diario_id.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $diario = TipoDocumento::findOrFail($id);
        $diario->designacao = $request->designacao;
        $diario->diario_id = $request->diario_id;
        $diario->numero = $request->numero;
        $diario->estado = $request->estado;
        $diario->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
