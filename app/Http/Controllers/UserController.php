<?php

namespace App\Http\Controllers;

use App\Models\EstadoCivil;
use App\Models\Pessoa;
use App\Models\Sexo;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    use TraitHelpers;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    
    public function permissions_utilizadores(Request $request, $id)
    {

        $user = auth()->user();
        
        if($user->primeiro_log == false){
            return redirect()->route('mf.privacidade');
        }

        $user = User::findOrFail($id);
        
        // $permission = Permission::findByName('VALIDAR PAGAMENTOS'); // Substitua com a permissão desejada

        $data['listagem_permissions'] = Permission::where('sistema', 'finance')->get();
        
        $data['permissions_user'] = $user->permissions;
        $data['user'] = $user;
    
        return Inertia::render('GestaoPermissions/utilizadores/Permission-Utilizadores', $data);
    }  
    
    public function lista_utilizadores(Request $request)
    {
           
        $user = auth()->user();
        
        if($user->primeiro_log == false){
            return redirect()->route('mf.privacidade');
        }
        
        $data['sexos'] = Sexo::select('Codigo AS id', 'Designacao AS text')->get();
        $data['estado_civil'] = EstadoCivil::select('Codigo AS id', 'Designacao AS text')->get();
        $data['tipos_documentos'] = TipoDocumento::select('Codigo AS id', 'Designacao AS text')->get();
        
        $data["utilizadores"] = User::whereIn('user_pertence', ['Finance','Finance-Cash', 'Todos'])
        ->when($request->nome_text_input, function($query, $value){
            $query->where('nome', "like" ,"%{$value}%");
        })
        ->with(['roles', 'pessoa'])
        ->where('active_state', 1)
        ->paginate($request->page_size ?? 10);
        
        $data['roles'] = Role::select('id', 'name AS text', 'sistema')->where('sistema', 'finance')->get();
    
        return Inertia::render('GestaoPermissions/utilizadores/Index', $data);
    }  
    
    public function lista_utilizadores_geral(Request $request)
    {
       
        $user = auth()->user();
        
        if($user->primeiro_log == false){
            return redirect()->route('mf.privacidade');
        }
        
        $data['sexos'] = Sexo::select('Codigo AS id', 'Designacao AS text')->get();
        $data['estado_civil'] = EstadoCivil::select('Codigo AS id', 'Designacao AS text')->get();
        $data['tipos_documentos'] = TipoDocumento::select('Codigo AS id', 'Designacao AS text')->get();
        
        $data["utilizadores"] = User::whereNull('user_pertence')
        ->when($request->nome_text_input, function($query, $value){
            $query->where('nome', "like" ,"%{$value}%");
        })
        ->with(['roles', 'pessoa'])
        ->where('active_state', 1)
        ->paginate($request->page_size ?? 10);
        
        $data['roles'] = Role::select('id', 'name AS text', 'sistema')->where('sistema', 'finance')->get();
    
        return Inertia::render('GestaoPermissions/utilizadores/ListagemGeral', $data);
    }  
    
    public function criar_utilizadores(Request $request)
    {


        $request->validate([
            "nome" => 'required',
            "senha" => 'required',
            "confirmar_senha" => 'required',
            "email" => 'required',
            "bilheite" => 'required',
        ], []);
        
        try {
            if($request->senha != $request->confirmar_senha){
                return response()->json(['error' => 'Senhas diferentes', 'status' => 404]);
            }
            
            // dd($request->all(), $request->user_pertence);
                        
            $create_pessoa = Pessoa::create([
                'nome_completo' => $request->nome,
                'nome_do_pai' => NULL,
                'nome_da_mae' => NULL,
                'data_de_nascimento' => $request->data_de_nascimento,
                'num_doc_identificacao' => $request->bilheite,
                'fk_tipo_documento_identificacao' => $request->tipo_documento,
                'data_de_emissao_documento' => NULL,
                'data_de_expiracao_documento' => NULL,
                'fk_genero' => $request->genero,
                'fk_nacionalidade' => 25,
                'endereco' => NULL,
                'fk_naturalidade' => 1,
                'fk_estado_civil' => $request->estado_civil,
                'telefone1' => $request->telefone,
                'telefone2' => $request->telefone,
                'email' => $request->email,
            ]);
            
            
            $dados = [
                "pk"=> $create_pessoa->pk_pessoa, 
                "desc"=> $create_pessoa->nome_completo
            ];
            
            $json = json_encode($dados);
            
            $user_mutue['tipoUtilizador'] = null;
            $usernames = preg_split('/\s+/', strtolower($request->nome), -1, PREG_SPLIT_NO_EMPTY);
            $username = head($usernames) . '.' . last($usernames);
            
            $create = User::create([
                'nome' => $request->nome,
                'userName' => $username,
                'password' => md5($request->senha) ,
                'user_pertence' => $request->user_pertence,
                'ref_pessoa' => $json,
            ]);
            
            $numeroAleatorio = rand(1000, 9999);
            
            $create->codigo_importado = $create->pk_utilizador . $numeroAleatorio;
            $create->user_pertence = $request->user_pertence;
            $create->update();
            
            $user = User::with('roles')->where('codigo_importado', $create->codigo_importado)->first();
            $roles = Role::findById($request->role_id);
            $user->assignRole($roles);
                   
                        
            return response()->json(['message' => 'Dados salvos com sucesso', 'status' => 200]);
            //code...
        } catch (\Throwable $th) {
        
            return response()->json(['error' => 'Não foi possível cadastrar utilizadores', 'status' => 404]);
        }
    
    }
        
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPerfilUtilizador($user_id)
    {
        $user = auth()->user();
        
        $user = User::with(['roles'])
            ->where('codigo_importado', $user_id)
            ->first();
     
        return response()->json(['utilizador' => $user]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mudarDeUserNosSistema($user_id, $sistema)
    {
        
        if($sistema == "Nenhum") {
            $sistema = NULL;
        }
        
        $operador = User::findOrFail($user_id);
        
        $operador->user_pertence = $sistema;
        $operador->update();
     
        return redirect()->back();
    }
    
    public function getReceentarSenha($user_id)
    {
        $user = auth()->user();
        
        $operador = User::findOrFail($user_id);
        $operador->password = md5("#M.financas@123");
        $operador->update();
        
        return redirect()->back();
    } 
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function removerPerfilUtilizador($id)
    {
        $user = auth()->user();

        $user = User::with('roles')->where('codigo_importado', $id)->first();
        
        foreach ($user->roles as $role){
            $user->removeRole($role);
        }

        return redirect()->back();
    }
            
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
     
    public function adicionar_perfil_utilizador(Request $request)
    {
        $user = auth()->user();
        
        $request->validate(
            ['role_id' => 'required'],
            ['user_id' => 'required'],
            ['role_id.required' => "Obrigatória"],
            ['user_id.required' => "Obrigatória"]
        ); 
              
        try {
            
            $user = User::with('roles')->where('codigo_importado', $request->user_id)->first();
                     
            foreach ($user->roles as $role){
                $user->removeRole($role);
            }
            
            foreach ($request->role_id as $value) {
                $roles = Role::findById($value);
                $user->assignRole($roles);
            }
         
            return response()->json(['message' => "Perfil actualizado com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Error ao! {$th}"]);
        }
    }
    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPermissionsRole($role_id)
    {
        $user = auth()->user();
        
        $permissions = Role::with('permissions')
            ->where('id', $role_id)
            ->first();
        return response()->json(['role' => $permissions]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function excluirRole($role_id)
    {
        $user = auth()->user();
        
        $role = Role::findById($role_id);
       
        $role->delete();
        
        return response()->json(['message' => "Perfil eliminado com sucesso!"]);
    }
        
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_Role(Request $request, $role_id)
    {
        $user = auth()->user();
        
        $role = Role::findById($role_id);
        $role->name = $request->name;
        $role->update();
 
        return response()->json(['message' => "Perfil Editado com sucesso!"]);
    }
               
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adicionar_permissions_utilizador(Request $request)
    {

        $user = User::findOrFail($request->user_id);
      
        $request->validate([
            'user_id' => 'required',
            'permissions_id' => 'required',
        ],[
            'user_id.required' => "Obrigatória",
            'permissions_id.required' => "Obrigatória"
        ]); 
         
       
        // try {
    
        foreach ($user->permissions as $permission) {
            $user->revokePermissionTo($permission);
        }

        if($request->permissions_id){
            
            foreach ($request->permissions_id as $per) {
                
                $permission = Permission::find($per);
                
                $user->givePermissionTo($permission);
            }
        }
      
        return response()->json(['message' => "Permissões actualizado com sucesso!"]);
        //     //code...
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return response()->json(['error' => "Error ao! {$th}"]);
        // }

    }
    
        
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adicionar_permissions(Request $request)
    {
        $user = auth()->user();
        
        $request->validate(
            ['role_id' => 'required'],
            ['permissions_id' => 'required'],
            ['role_id.required' => "Obrigatória"],
            ['permissions_id.required' => "Obrigatória"]
        ); 
         
        try {
    
            $permissions = Role::with('permissions')->where('id', $request->role_id)->first();
            $role = Role::findById($request->role_id);
    
            foreach ($permissions->permissions as $permission) {
                $permission->removeRole($role);
            }
    
            if($request->permissions_id){
                foreach ($request->permissions_id as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
            
            return response()->json(['message' => "Perfil actualizado com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Error ao! {$th}"]);
        }

    }    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function privacidade(Request $request)
    {
               
        $user = auth()->user();
        
        // if($user->primeiro_log == true){
        //     return redirect()->route('mf.painel-principal');
        // }else{
        //     return redirect()->route('mf.privacidade');
        // }
                
        $data['users'] = $user;
    
        return Inertia::render('GestaoPermissions/privacidade/Index', $data);

    }    
    
    public function actualizar_credencias(Request $request)
    {    
        $request->validate([
            "senha_actual" => 'required',
            "nova_senha" => 'required',
            "confirmar_nova_senha" => 'required',
        ], []);
        
        $user = auth()->user();

        try {
        
            if(md5($request->senha_actual) !=  $user->password){
                return response()->json(['error' => 'Senha actual não confere!', 'status' => 404]);
            }
            
            if($request->nova_senha !=  $request->confirmar_nova_senha){
                return response()->json(['error' => 'Nova senha e confirmação de senha não conferem!', 'status' => 404]);
            }
       
            $update = User::findOrFail($user->pk_utilizador);
            $update->password = md5($request->nova_senha);
            $update->primeiro_log = 1;
            $update->update();
            
            return response()->json(['message' => 'Senha alterada com sucesso', 'status' => 200]);
            //code...
        } catch (\Throwable $th) {
        
            return response()->json(['error' => 'Não foi possível alterar a senha', 'status' => 404]);
        }

    }
               
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function meus_dados(Request $request)
    {
           
        $user = auth()->user();
        
        if($user->primeiro_log == false){
            return redirect()->route('mf.privacidade');
        }
        
        $arrayData = json_decode($user->ref_pessoa, true);
        
        $pkValue = $arrayData['pk'];
        
        $dados = Pessoa::with('genero', 'estado_civil', 'tipos_documentos')->find($pkValue);
      
        $data['dados'] = $dados;
        $data['user'] = $user;
        $data['sexos'] = Sexo::select('Codigo AS id', 'Designacao AS text')->get();
        $data['estado_civil'] = EstadoCivil::select('Codigo AS id', 'Designacao AS text')->get();
        $data['tipos_documentos'] = TipoDocumento::select('Codigo AS id', 'Designacao AS text')->get();
        
        // ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.codigo_importado')
    
        return Inertia::render('GestaoPermissions/privacidade/meus-dados', $data);

    }
    
    
    public function actualizar_dados(Request $request)
    {    
        $request->validate([
            "nome" => 'required',
            "genero" => 'required',
            "estado_civil" => 'required',
            "email" => 'required',
        ], []);
                
        $user = auth()->user();

        try {
        
            $dado = Pessoa::find($request->codigo);
        
            $dado->nome_completo = $request->nome;
            // $dado->nome_do_pai = $request->;
            // $dado->nome_da_mae = $request->;
            $dado->data_de_nascimento = $request->data_nascimento;
            $dado->num_doc_identificacao = $request->bilheite;
            $dado->fk_tipo_documento_identificacao = $request->tipo_documento;
            $dado->data_de_emissao_documento = $request->data_emissao;
            $dado->data_de_expiracao_documento = $request->data_expiracao;
            $dado->fk_genero = $request->genero;
            // $dado->fk_nacionalidade = $request->;
            // $dado->endereco = $request->;
            // $dado->fk_naturalidade = $request->;
            $dado->fk_estado_civil = $request->estado_civil;
            $dado->telefone1 = $request->telefone;
            $dado->telefone2 = $request->telefone2;
            $dado->email = $request->email;
            $dado->update();
       
            $update = User::findOrFail($user->pk_utilizador);
            $update->nome = $request->nome;
            $update->update();
            
            return response()->json(['message' => 'Senha alterada com sucesso', 'status' => 200]);
            //code...
        } catch (\Throwable $th) {
        
            return response()->json(['error' => 'Não foi possível alterar a senha', 'status' => 404]);
        }

    }
        
}
