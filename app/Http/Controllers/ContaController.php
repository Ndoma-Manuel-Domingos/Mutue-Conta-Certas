<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\SubConta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class ContaController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['contas'] = ContaEmpresa::whereHas('conta', function($query) use($request){
            $query->when($request->input_busca_contas, function($query, $value){
                $query->where('designacao', 'like', "%".$value."%");
                $query->orWhere('numero', 'like', "%".$value."%");
            }); 
        })
        ->whereHas('classe', function($query) use($request){
            $query->when($request->input_busca_contas, function($query, $value){
                $query->where('designacao', 'like', "%".$value."%");
                $query->orWhere('numero', 'like', "%".$value."%");
            }); 
        })
        
        // ->when($request->order_by, function($query, $value){
        //     if($value == "conta"){
        //         $query->orderBy('conta_id', 'asc');
        //     }else if($value == "numero"){
        //         $query->orderBy('numero', 'asc');
        //     }else if($value == "designacao"){
        //         $query->orderBy('designacao', 'asc');
        //     }
        // })
               
        
        ->with(['empresa', 'conta', 'classe'])->where('empresa_id', $this->empresaLogada())
        // ->orderBy('numero', 'desc')
        ->paginate(10);
               
        return Inertia::render('Contas/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['contas'] = Conta::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

       
        return Inertia::render('Contas/Create', $data);
    }

    public function store(Request $request)
    {

        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $request->validate([
            "classe_id" => "required",
            "conta_id" => "required",
            "tipo" => "required",
            "numero" => "required",
            "estado" => "required",
        ], 
        [
            "classe_id.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        
        if($this->empresaLogada()){
            $classes =  ContaEmpresa::create([
                'classe_id' => $request->classe_id,
                'conta_id' => $request->conta_id,
                'tipo' => $request->tipo,
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


    public function show($id)
    {
    
        $empresa = ContaEmpresa::findOrFail($id);
        
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


    public function get_conta($id)
    {
        $conta = Conta::findOrFail($id);
        $subcontas = SubConta::with(['empresa', 'conta'])->where('conta_id', $conta->id)->orderBy('id', 'asc')->get();
        
        return response()->json(
            [
                'conta' => $conta, 
                "subcontas"=> $subcontas
            ]
        , 200);
    }
    
    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['conta'] = ContaEmpresa::findOrFail($id);
        
        $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['contas'] = Conta::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('Contas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "classe_id" => "required",
            "tipo" => "required",
            "conta_id" => "required",
            "numero" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "classe_id.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $classe = ContaEmpresa::findOrFail($id);
        $classe->classe_id = $request->classe_id;
        $classe->conta_id = $request->conta_id;
        $classe->numero = $request->numero;
        $classe->tipo = $request->tipo;
        $classe->estado = $request->estado;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirContas(){
        
        $data['contas_data'] = ContaEmpresa::with(['empresa', 'conta', 'classe'])->get();     
        // dd($data['contas_data']);
        $pdf = PDF::loadView('pdf.contas.Contas', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Contas.pdf');
    }
}
