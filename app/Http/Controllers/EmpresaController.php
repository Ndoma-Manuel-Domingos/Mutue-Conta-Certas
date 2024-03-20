<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\ContactoEmpresa;
use App\Models\ContaEmpresa;
use App\Models\DocumentoEmpresa;
use App\Models\Empresa;
use App\Models\EnderecoEmpresa;
use App\Models\Exercicio;
use App\Models\Moeda;
use App\Models\MoedaEmpresa;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\Provincia;
use App\Models\Regime;
use App\Models\TipoContactoEmpresa;
use App\Models\TipoDocumentoEmpresa;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\UserEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data['tipos_documentos_empresa'] = TipoDocumentoEmpresa::select('id', 'designacao As text')->get();
        $data['tipos_contactos_empresa'] = TipoContactoEmpresa::select('id', 'designacao As text')->get();
        $data['moedas'] = Moeda::select('id', 'designacao As d', DB::raw('CONCAT(sigla, " - ", designacao) AS text'))->get();
              
        return Inertia::render('Empresas/Create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
                     
        // dd($request->all());            
                     
        // Exemplo de retorno de erros de validação em JSON
        $validator = Validator::make($request->all(), [
            "nome_empresa" => "required",
            "codigo_empresa" => "required",
        ]);     
             
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
             
        try {
            DB::beginTransaction();
    
            // Sua lógica de salvamento dos dados aqui
                        
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
            
            $user_empresa = UserEmpresa::create([
                'estado' => 1,
                'empresa_id' => $empresa->id,
                'user_id' => auth()->user()->id,
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
            
            if($request->contactos_empresa){
                foreach ($request->contactos_empresa as $contacto) {
                    ContactoEmpresa::create([
                        'contacto_empresas' => $contacto['tipo_contacto_empresa_designacao'],
                        'tipo_contacto_empresa_id' => $contacto['tipo_contacto_empresa_id'],
                        'empresa_id' => $empresa->id,
                        'created_by' => auth()->user()->id,
                    ]);
                }
            }
           
            if($request->documentos_empresa){
                foreach ($request->documentos_empresa as $documento) {
                    DocumentoEmpresa::create([
                        'numero_documento_empresa' => $documento['tipo_documento_empresa_designacao'],
                        'tipo_documento_empresa_id' => $documento['tipo_documento_empresa_id'],
                        'empresa_id' => $empresa->id,
                        'created_by' => auth()->user()->id,
                    ]);
                }
            }
    
            // Se tudo estiver bem, comitar as alterações no banco de dados
            DB::commit();
    
            return response()->json(['message' => 'Dados salvos com sucesso!'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, desfazer as alterações no banco de dados
            DB::rollback();
    
            // Retornar uma resposta JSON com o erro
            return response()->json(['message' => 'Erro ao salvar os dados: ' . $e->getMessage()], 500);
        }
             
        
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
        
        $endereco =  EnderecoEmpresa::find($request->itemIdEnderco);
        
        if($endereco){
            $endereco->rua = $request->rua;
            $endereco->casa = $request->casa;
            $endereco->bairro = $request->bairro;
            $endereco->codigo_postal = $request->codigo_postal;
            $endereco->pais_id = $request->pais_id;
            $endereco->provincia_id = $request->provincia_id;
            $endereco->municipio_id = $request->municipio_id;
            $endereco->comuna_id = $request->comuna_id;
            $endereco->update();
        }else{
            
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
        
        }
        
        $moeda =  MoedaEmpresa::find($request->itemIdMoeda);
        
        if($moeda){
            $moeda->moeda_base_id = $request->moeda_base_id;
            $moeda->moeda_alternativa_id = $request->moeda_alternativa_id;
            $moeda->updated_by = auth()->user()->id;
            $moeda->update();
        }else{
            $moedas =  MoedaEmpresa::create([
                'moeda_base_id' => $request->moeda_base_id,
                'moeda_alternativa_id' => $request->moeda_alternativa_id,
                'moeda_cambio_id' => $request->moeda_cambio_id,
                'empresa_id' => $empresa->id,
                'created_by' => auth()->user()->id,
            ]);
        }
        
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    public function iniciar_sessao($id)
    {
        $empresa = UserEmpresa::where('empresa_id', $id)->where('user_id', Auth::user()->id)->first();
        
        try {
            DB::beginTransaction();
            
            if($empresa){
                
                Session::forget('empresa_logada_mutue_contas_certas_2024');
            
                $empresas = UserEmpresa::where('user_id', Auth::user()->id)->get();
                
                foreach ($empresas as $e) {
                    $update = UserEmpresa::findOrFail($e->id);
                    $update->estado = 2;
                    $update->update();
                }
                
                $exercicios = Exercicio::where('empresa_id', $id)->get();
                foreach ($exercicios as $item) {
                    $update = Exercicio::findOrFail($item->id);
                    $update->estado = 2;
                    $update->update();
                }
                
                $empresa_a_operar = UserEmpresa::with('empresa')->findOrFail($empresa->id);
                $empresa_a_operar->estado = 1;
                $empresa_a_operar->update();
                
                $empresa_array = [
                    'id' => $empresa_a_operar->empresa->id,
                    'nome' => $empresa_a_operar->empresa->nome_empresa,
                    'nif' => $empresa_a_operar->empresa->codigo_empresa,
                ];
                
                // Colocar um novo valor na sessão global
                Session::put('empresa_logada_mutue_contas_certas_2024', $empresa_array);
          
            
            }else{
                return redirect()->json(['error', "Sem permissão para operar neste empresa!"], 404);
            }    
            // Se tudo estiver bem, comitar as alterações no banco de dados
            DB::commit();
    
            return response()->json(['message' => 'Dados salvos com sucesso!'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, desfazer as alterações no banco de dados
            DB::rollback();
    
            // Retornar uma resposta JSON com o erro
            return response()->json(['message' => 'Erro ao salvar os dados: ' . $e->getMessage()], 500);
        }
        
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);


    }
    
    public function logout_empresa(Request $request)
    {
        $empresa = UserEmpresa::where('estado', '1')->where('user_id', Auth::user()->id)->first();
        $empresa->estado = 2;
        $empresa->update();
        
        Session::forget('empresa_logada_mutue_contas_certas_2024');

        return redirect('/empresas');

    } 
    
        
    public function escolher_empresa_operar(Request $request)
    {    
        // Session::forget('empresa_logada_mutue_contas_certas_2024');

        // return redirect('/empresas');

    }  

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
    
}
