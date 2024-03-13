<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\ContaEmpresa;
use App\Models\Empresa;
use App\Models\EnderecoEmpresa;
use App\Models\Moeda;
use App\Models\MoedaEmpresa;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\Provincia;
use App\Models\Regime;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    //
    public function index()
    {
        // Retorna a lista de posts
        $user = User::with('empresa')->findOrFail(auth()->user()->id);
         
        $data['empresas'] = Empresa::with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio'])->where('user_id', $user->id)->paginate(7);
    
        return Inertia::render('Empresas/Index', $data);
    }

    public function create()
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['paises'] = Pais::select('id', 'designacao As text')->get();
        $data['provincias'] = Provincia::select('id', 'designacao As text')->get();
        $data['municipios'] = Municipio::select('id', 'designacao As text')->get();
        $data['comunas'] = Comuna::select('id', 'designacao As text')->get();
        $data['regimes'] = Regime::select('id', 'designacao As text')->get();
        $data['moedas'] = Moeda::select('id', 'designacao As d', DB::raw('CONCAT(sigla, " - ", designacao) AS text'))->get();
              
        return Inertia::render('Empresas/Create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
             
        // Exemplo de retorno de erros de validação em JSON
        $validator = Validator::make($request->all(), [
            "nome_empresa" => "required",
            "codigo_empresa" => "required",
        ]);     
             
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $empresa =  Empresa::create([
            'nome_empresa' => $request->nome_empresa,
            'codigo_empresa' => $request->codigo_empresa,
            'descricao_empresa' => $request->descricao_empresa,
            'logotipo_da_empresa' => "",
            'estado_empresa_id' => $request->estado_empresa_id,
            'regime_empresa_id' => $request->regime_empresa_id,
            'user_id' => auth()->user()->id,
            'created_by' => auth()->user()->id,
        ]);
        
        $endereco =  EnderecoEmpresa::create([
            'rua' => $request->rua,
            'casa' => $request->casa,
            'bairro' => $request->bairro,
            'codigo_postal' => $request->codigo_postal,
            'pais_id' => $request->pais_id,
            'provincia_id' => $request->provincia_id,
            'municipio_id' => $request->municipio_id,
            'comuna_id' => $request->comuna_id,
            'empresa_id' => $empresa->id,
        ]);
        
        $moedas =  MoedaEmpresa::create([
            'moeda_base_id' => $request->moeda_base_id,
            'moeda_alternativa_id' => $request->moeda_alternativa_id,
            'moeda_cambio_id' => $request->moeda_cambio_id,
            'empresa_id' => $empresa->id,
            'created_by' => auth()->user()->id,
        ]);
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
    }
    

    public function show($id)
    {
    
        $empresa = Empresa::with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio'])->findOrFail($id);
        
        $estado = "";
        
        if($empresa->estado_empresa_id == 1){
            $estado = 2;
        }
        if($empresa->estado_empresa_id == 2){
            $estado = 1;
        }
        
        $empresa->estado_empresa_id = $estado;
        $empresa->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function edit($id)
    {
        // Exibe o formulário para editar um post

        $data['paises'] = Pais::select('id', 'designacao As text')->get();
        $data['provincias'] = Provincia::select('id', 'designacao As text')->get();
        $data['municipios'] = Municipio::select('id', 'designacao As text')->get();
        $data['comunas'] = Comuna::select('id', 'designacao As text')->get();
        $data['regimes'] = Regime::select('id', 'designacao As text')->get();
        $data['moedas'] = Moeda::select('id', 'designacao As d', DB::raw('CONCAT(sigla, " - ", designacao) AS text'))->get();
        
        $data['empresa'] = Empresa::with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio'])->findOrFail($id);
                       
        return Inertia::render('Empresas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
    
        $validator = Validator::make($request->all(), [
            "nome_empresa" => "required",
            "codigo_empresa" => "required",
        ]);     
             
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $empresa =  Empresa::findOrFail($id);
        
        $empresa->nome_empresa = $request->nome_empresa;
        $empresa->codigo_empresa = $request->codigo_empresa;
        $empresa->descricao_empresa = $request->descricao_empresa;
        $empresa->estado_empresa_id = $request->estado_empresa_id;
        $empresa->regime_empresa_id = $request->regime_empresa_id;
        $empresa->updated_by = auth()->user()->id;
        $empresa->update();
        
        $endereco =  EnderecoEmpresa::findOrFail($request->itemIdEnderco);
        
        $endereco->rua = $request->rua;
        $endereco->casa = $request->casa;
        $endereco->bairro = $request->bairro;
        $endereco->codigo_postal = $request->codigo_postal;
        $endereco->pais_id = $request->pais_id;
        $endereco->provincia_id = $request->provincia_id;
        $endereco->municipio_id = $request->municipio_id;
        $endereco->comuna_id = $request->comuna_id;
        $endereco->update();
        
        $moeda =  MoedaEmpresa::findOrFail($request->itemIdMoeda);
        
        $moeda->moeda_base_id = $request->moeda_base_id;
        $moeda->moeda_alternativa_id = $request->moeda_alternativa_id;
        $moeda->moeda_cambio_id = $request->moeda_cambio_id;
        $moeda->updated_by = auth()->user()->id;
        $moeda->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    public function iniciar_sessao($id)
    {
        $empresa = Empresa::with(['endereco', 'regime', 'moeda.base', 'moeda.alternativa', 'moeda.cambio'])->findOrFail($id);
        
        $empresa_array = [
            'id' => $empresa->id,
            'nome' => $empresa->nome_empresa,
            'nif' => $empresa->codigo_empresa,
        ];
        
        // Colocar um novo valor na sessão global
        Session::put('empresa_logada_mutue_contas_certas_2024', $empresa_array);
  
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    public function logout_empresa(Request $request)
    {
    
        Session::forget('empresa_logada_mutue_contas_certas_2024');

        return redirect('/empresas');

    } 
    
        
    public function escolher_empresa_operar(Request $request)
    {
        dd("escolher Empresa para operar");
    
        // Session::forget('empresa_logada_mutue_contas_certas_2024');

        // return redirect('/empresas');

    }  

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
    
}
