<?php

namespace App\Http\Controllers;

use App\Models\ContaEmpresa;
use App\Models\Contrapartida;
use App\Models\Documento;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\SubConta;
use App\Models\TipoCredito;
use App\Models\TipoMovimento;
use App\Models\TipoProveito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    use Config;
    //
    public function criar_subconta(Request $request)
    {
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                    
            $verificar_subconta = SubConta::where('numero', $request->numero)->where('empresa_id', $this->empresaLogada())->where('tipo_instituicao', $request->tipo_instituicao)->get();
            
            if(count($verificar_subconta) > 0){
                return response()->json([
                    'code' => 400,
                    'message' => 'Este conta Já existe cadastrada!'
                ], 400);
            }
                    
            $subconta = SubConta::create([
                'conta_id' => $request->conta_id,
                'designacao' => $request->designacao,
                'descricao' => $request->designacao,
                'numero' => $request->numero,
                'estado' => 'activo',
                'tipo' => $request->tipo,
                'tipo_instituicao' => $request->tipo_instituicao,
                'instituicao_id' => $request->instituicao_id,
                'empresa_id' => $request->empresa_id,
            ]);
  
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
          
        return response()->json($subconta, 201);

    }
    
    public function criar_debito(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                    
            $hash = time();
            
            
            $subconta_cliente = SubConta::with(['conta'])->where('tipo_instituicao', $request->tipo_instituicao)->where('numero', $request->subconta_cliente)->first();
            $subconta_caixa_banco = SubConta::with(['conta'])->where('numero', $request->subconta_caixa_banco)->first();
            $subconta_servico = SubConta::with(['conta'])->where('numero', $request->subconta_servico)->first();
            
            // return response()->json([
            //     "cliente" => $subconta_cliente,
            //     "caixa" => $subconta_caixa_banco,
            //     "servico" => $subconta_servico,
            // ]);
            
            $tipo_movimento = TipoMovimento::where('sigla', 'D')->first();
            
            $ultimo_movimento = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->count();
            
            $hash = time();
                    
            $create = Movimento::create([
                'hash' => $hash,
                'debito' => $request->valor,
                'credito' => $request->valor,
                'iva' => 0,
                'empresa_id' => 1,
                'descricao' => $request->designacao,
                'requisitante' => 1,
                'centro_custo' => 1,
                // 'requisitante' => $request->requisitante,
                // 'centro_custo' => $request->centro_custo,
                'origem' => "movimento",
                'exercicio_id' => 1,
                'periodo_id' =>  5,
                'dia_id' => date("d"),
                'data_lancamento' => date("Y-m-d"),
                'lancamento_atual' => $ultimo_movimento + 1,
                'diario_id' => 2, // $request->diario_id,
                'tipo_documento_id' => 2, //$request->tipo_documento_id,
                'tipo_instituicao' => $request->tipo_instituicao,
                'instituicao_id' => $request->instituicao_id,
            ]);
            
            // DEBITAR
            MovimentoItem::create([
                'hash' => $hash,
                'debito' => $request->valor,
                'credito' => 0,
                'iva' => 0,
                'descricao' => $request->designacao,
                'origem' => "movimento",
                'empresa_id' => 1,
                'subconta_id' => $subconta_caixa_banco->id,
                'conta_id' => $subconta_caixa_banco->conta_id,
                'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                'documento_id' => NULL,
                'tipo_credito_id' => NULL,
                'tipo_proveito_id' => NULL,
                'contrapartida_id' =>  NULL,
                'taxta_iva_id' => 1,
                'apresentar' => 'S',
                'movimento_id' => $create->id,
                'tipo_instituicao' => $request->tipo_instituicao,
                'instituicao_id' => $request->instituicao_id,
            ]);
            
            //  CREDITAR
            MovimentoItem::create([
                'hash' => $hash,
                'debito' => 0,
                'credito' => $request->valor,
                'iva' => 0,
                'descricao' => $request->designacao,
                'origem' => "movimento",
                'empresa_id' => 1,
                'subconta_id' => $subconta_servico->id,
                'conta_id' => $subconta_servico->conta_id,
                'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                'documento_id' => NULL,
                'tipo_credito_id' => NULL,
                'tipo_proveito_id' => NULL,
                'contrapartida_id' =>  NULL,
                'taxta_iva_id' => 1,
                'apresentar' => 'S',
                'movimento_id' => $create->id,
                'tipo_instituicao' => $request->tipo_instituicao,
                'instituicao_id' => $request->instituicao_id,
            ]);
            
            // DEBITAR E CREDITAR
            MovimentoItem::create([
                'hash' => $hash,
                'debito' => $request->valor,
                'credito' => $request->valor,
                'iva' => 0,
                'descricao' => $request->designacao,
                'empresa_id' => 1,
                'subconta_id' => $subconta_cliente ? $subconta_cliente->id : NULL,
                'conta_id' => $subconta_cliente ? $subconta_cliente->conta_id : NULL,
                'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                'documento_id' => NULL,
                'tipo_credito_id' => NULL,
                'tipo_proveito_id' => NULL,
                'contrapartida_id' => NULL,
                'taxta_iva_id' => 1,
                'origem' => 'movimento',
                'movimento_id' => $create->id,
                'apresentar' => 'N',
                'movimento_id' => $create->id,
                'tipo_instituicao' => $request->tipo_instituicao,
                'instituicao_id' => $request->instituicao_id,
            ]);
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
          
        return response()->json($create, 201);

    }
    
    public function criar_credito(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                    
            $hash = time();
            
            $subconta = SubConta::find($request->sub_conta_id);
            $tipo_proveito = TipoProveito::find($request->tipo_proveito_id);
            $tipo_credito = TipoCredito::find($request->tipo_credito_id);
            $contrapartida = Contrapartida::find($request->contrapartida_id);
            $documento = Documento::find($request->documento_id);
    
            $tipo_movimento = TipoMovimento::find($request->tipo_movimento_id);
            
            $ultimo_movimento = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])->where('empresa_id', $this->empresaLogada())->count();
                    
            $create = Movimento::create([
                'hash' => $hash,
                'debito' => 0,
                'credito' => $request->valor,
                'iva' => 0,
                'empresa_id' => $this->empresaLogada(),
                'descricao' => $request->designacao,
                'requisitante' => $request->requisitante,
                'centro_custo' => $request->centro_custo,
                'origem' => "movimento",
                'exercicio_id' => $this->exercicioActivo(),
                'periodo_id' =>  $this->periodoActivo(),
                'dia_id' => date("d"),
                'data_lancamento' => date("Y-m-d"),
                'lancamento_atual' => $ultimo_movimento + 1,
                'diario_id' => 2, // $request->diario_id,
                'tipo_documento_id' => 2, //$request->tipo_documento_id,
                'tipo_instituicao' => $request->tipo_instituicao,
                // 'user_id' => $user->id,
                // 'created_by' => $user->id,
            ]);

            MovimentoItem::create([
                'hash' => $hash,
                'debito' => 0,
                'credito' => $request->valor,
                'iva' => 0,
                'descricao' => $request->designacao,
                'origem' => "movimento",
                'empresa_id' => $this->empresaLogada(),
                'subconta_id' => $subconta->id,
                'conta_id' => $subconta->conta_id,
                'tipo_movimento_id' => $tipo_movimento ? $tipo_movimento->id : NULL,
                'documento_id' => $documento ? $documento->id : NULL,
                'tipo_credito_id' => $tipo_credito ? $tipo_credito->id : NULL,
                'tipo_proveito_id' => $tipo_proveito ? $tipo_proveito->id : NULL,
                'contrapartida_id' => $contrapartida ? $contrapartida->id : NULL,
                'taxta_iva_id' => 1,
                'apresentar' => 'S',
                'movimento_id' => $create->id,
                'tipo_instituicao' => $request->tipo_instituicao,
                // 'user_id' => $user->id,
                // 'created_by' => $user->id,
            ]);        
                    
     
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
          
        return response()->json($subconta, 201);

    }
    
    public function lista_movimentos(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                    
            $movimentos = Movimento::with(['exercicio', 'periodo' , 'items'])
            ->where('tipo_instituicao', $request->tipo_instituicao)
            ->where('instituicao_id', $request->instituicao_id)
            ->orderBy('id', $request->order_by ?? 'desc')->get();
     
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
          
        return response()->json($movimentos, 201);

    }
    
    public function lista_movimentos_by_id($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $movimento = Movimento::with(['exercicio', 'periodo' , 'items.subconta.conta'])->findOrFail($id);
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
          
        return response()->json($movimento, 201);

    }

    public function get_subconta(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            $subconta = SubConta::with(['conta'])->where('instituicao_id', $request->instituicao_id)->where('tipo_instituicao', $request->tipo_aplicacao)->get();
    
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json($subconta);

    }

    public function get_subconta_by(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            if($request->numero_conta){
                $subconta = SubConta::with(['conta'])->where('numero', $request->numero_conta)->first();
            }else if($request->id_conta){
                $subconta = SubConta::with(['conta'])->where('id', $request->id_conta)->first();
            }
    
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json($subconta);

    }
    
    public function edit_subconta($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
        
            $subconta = SubConta::with(['conta'])->findOrFail($id);
    
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        
        return response()->json($subconta);

    }

    public function update_subconta(Request $request, $id)
    {
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

    public function get_conta(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
    
            $subconta = ContaEmpresa::with(['conta', 'classe'])->where('empresa_id', $request->empresa_id)->get();
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return response()->json($e->getMessage(), 201);
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return $subconta;   
    }

    public function loginAPI(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            // 'instituicao' => 'required',
        ]);
        
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response()->json('acesso negado', 201);
        }

        $data['user'] = [
            "nome" => $user->name,
            "email" => $user->email,
        ];

        $data['token'] = $user->createToken($request->email)->plainTextToken;

        return response()->json($data);
    }

}
