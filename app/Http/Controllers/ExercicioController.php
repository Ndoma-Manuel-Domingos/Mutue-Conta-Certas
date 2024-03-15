<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Models\Classe;
use App\Models\ClasseEmpresa;
use App\Models\Exercicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use PDF;

class ExercicioController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $empresa_sessao_global = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $data['exercicios'] = Exercicio::where('empresa_id', $empresa_sessao_global['id'] ?? "")->with(['empresa'])->paginate(7);
               
        return Inertia::render('Exercicios/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post

        $data['anos'] = Ano::select('id', 'designacao As text')->get();
       
        return Inertia::render('Exercicios/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $validator = Validator::make($request->all(), [
            "designacao" => "required",
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $empresa_sessao_global = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        if($empresa_sessao_global){
        
            Exercicio::create([
                'designacao' => $request->designacao,
                'empresa_id' => $empresa_sessao_global['id'],
                'estado' => $request->estado,
            ]);
        }
        
        
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
        $data['exercicio'] = Exercicio::findOrFail($id);
        
        // $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('Exercicios/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "designacao" => "required",
        ], 
        [
            "estado.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $classe = Exercicio::findOrFail($id);
        $classe->estado = $request->estado;
        $classe->designacao = $request->designacao;
        $classe->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirExercicios(){
        $data['exercicio_data'] = Exercicio::with(['empresa'])->get();     
        
        $pdf = PDF::loadView('pdf.contas.Exercicio', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Contas.pdf');
    }
}
