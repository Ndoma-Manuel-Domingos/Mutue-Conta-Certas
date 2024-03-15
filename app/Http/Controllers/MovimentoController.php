<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\Exercicio;
use App\Models\MovimentoItem;
use App\Models\SubConta;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class MovimentoController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        // where('empresa_id', $users->empresa_id)->

        $data['movimentos'] = []; // TipoDocumento::with(['empresa', 'diario'])->paginate(10);
               
        return Inertia::render('Movimentos/Index', $data);
    }

    public function create()
    {
        $data['diarios'] = Diario::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['tipo_documentos'] = TipoDocumento::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
        $data['contas'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();
       
        return Inertia::render('Movimentos/Create', $data);
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
 

    public function adicionar_conta_movimento($id)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        $subconta = SubConta::findOrFail($id);
        
        $hash = time();
        
        $session = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $verificar_movimento = MovimentoItem::where('movimento_id', NULL)->where('user_id', $user->id)->where('empresa_id', $session['id'])->first();
        
        if($verificar_movimento){
        
            MovimentoItem::create([
                'hash' => $verificar_movimento->hash,
                'debito' => 0,
                'credito' => 0,
                'iva' => 0,
                'descricao' => "",
                'empresa_id' => $session['id'],
                'subconta_id' => $subconta->id,
                'movimento_id' => NULL,
                'user_id' => $user->id,
                'created_by' => $user->id,
            ]);
            
        }else{
            MovimentoItem::create([
                'hash' => $hash,
                'debito' => 0,
                'credito' => 0,
                'iva' => 0,
                'descricao' => "",
                'empresa_id' => $session['id'],
                'subconta_id' => $subconta->id,
                'movimento_id' => NULL,
                'user_id' => $user->id,
                'created_by' => $user->id,
            ]);
        }
        
        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $session['id'])->where('user_id', $user->id)->get();
        
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $session['id'])->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
        
   
    }   

    public function remover_conta_movimento($id)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        $movimento = MovimentoItem::findOrFail($id);
        $movimento->delete();
        
        $session = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $session['id'])->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $session['id'])->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
   
    }  
    
    public function alterar_debito_conta_movimento($id, $valor)
    {
    
        $user = User::findOrFail(auth()->user()->id);
        
        $movimento = MovimentoItem::findOrFail($id);
        $movimento->debito = $valor;
        $movimento->update();
        
        $session = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $session['id'])->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $session['id'])->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
    }    
    
    public function alterar_credito_conta_movimento($id, $valor)
    {
        $user = User::findOrFail(auth()->user()->id);
        
        $movimento = MovimentoItem::findOrFail($id);
        $movimento->credito = $valor;
        $movimento->update();
        
        $session = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $item_movimentos = MovimentoItem::with(['subconta.conta', 'empresa'])->where('movimento_id', NULL)->where('empresa_id', $session['id'])->where('user_id', $user->id)->get();
        $resultados = MovimentoItem::with(['subconta.conta', 'empresa'])->whereNull('movimento_id')->where('empresa_id', $session['id'])->where('user_id', $user->id)->selectRaw('SUM(debito) AS total_debito, SUM(credito) AS total_credito')->first();
        
        return response()->json(['item_movimentos' => $item_movimentos, 'resultados' => $resultados], 200);
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
