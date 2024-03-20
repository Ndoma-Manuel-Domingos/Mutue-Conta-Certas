<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\SubConta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class SubContaController extends Controller
{
    use Config;
    //
    public function index()
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['subcontas'] = SubConta::with(['empresa', 'conta'])->where('empresa_id', $this->empresaLogada())->paginate(7);
               
        return Inertia::render('SubContas/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $this->empresaLogada())->join('contas', 'controle_conta_empresas.conta_id' , '=', 'contas.id')
        ->select('contas.id', 'designacao As d', DB::raw('CONCAT(contas.numero, " - ", contas.designacao) AS text'))
        ->get();
                
        $data['subcontas'] = SubConta::with(['empresa', 'conta'])->orderBy('id', 'asc')->get();
    
        return Inertia::render('SubContas/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $request->validate([
            "conta_id" => "required",
            "designacao" => "required",
            "numero" => "required",
            "tipo" => "required",
            "estado" => "required",
        ], 
        [
            "conta_id.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);
        
        $subconta =  SubConta::create([
            'conta_id' => $request->conta_id,
            'designacao' => $request->designacao,
            'descricao' => $request->designacao,
            'numero' => $request->numero,
            'estado' => $request->estado,
            'tipo' => $request->tipo,
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
        
        $data['contas'] = ContaEmpresa::where('empresa_id', $this->empresaLogada())
        ->join('contas', 'controle_conta_empresas.conta_id' , '=', 'contas.id')
        ->select('contas.id', 'designacao As d', DB::raw('CONCAT(contas.numero, " - ", contas.designacao) AS text'))
        // ->select('contas.id', 'contas.designacao AS text')
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
            "tipo" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "conta_id.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
            "numero.required" => "Campo Obrigatório",
            "tipo.required" => "Campo Obrigatório",
        ]);
        


        // Atualiza um post específico no banco de dados
        $classe = SubConta::findOrFail($id);
        $classe->conta_id = $request->conta_id;
        $classe->designacao = $request->designacao;
        $classe->numero = $request->numero;
        $classe->estado = $request->estado;
        $classe->tipo = $request->tipo;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirSubContas(){
        $data['subConta_data'] = SubConta::with(['empresa', 'conta', 'empresa_conta'])->get();     
        
        $pdf = PDF::loadView('pdf.contas.SubConta', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Contas.pdf');
    }
}
