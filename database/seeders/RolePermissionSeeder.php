<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
 
        $permissao1 = Permission::create(['name' => 'VALIDAR PAGAMENTOS']);
        $permissao2 = Permission::create(['name' => 'CONSULTAR NUMEROS DE OPERACOES']);
        $permissao3 = Permission::create(['name' => 'TALAO EM DESUSO']);
        $permissao4 = Permission::create(['name' => 'ESTUDANTES INACTIVOS']);
        $permissao5 = Permission::create(['name' => 'ESTUDANTES FINALISTAS INACTIVOS']);
        $permissao6 = Permission::create(['name' => 'ESTUDANTES FINALISTAS']);
        $permissao7 = Permission::create(['name' => 'CONTROLO DE ACTUALIZACOES DE SALDO']);
        
        $permissao8 = Permission::create(['name' => 'LISTAGEM GERAL ESTUDANTES']);
        $permissao865 = Permission::create(['name' => 'VISUALIZAR ESTUDANTE']);
        $permissao9 = Permission::create(['name' => 'LISTAR ESTUDANTES MATRICULADOS']);
        $permissao10 = Permission::create(['name' => 'LISTAR ESTUDANTES POR ESTADO']);
        
        $permissao11 = Permission::create(['name' => 'LISTAR BOLSEIROS']);
        $permissao121 = Permission::create(['name' => 'EDITAR BOLSEIROS']);
        $permissao1211 = Permission::create(['name' => 'VISUALIZAR BOLSEIROS']);
        $permissao12112 = Permission::create(['name' => 'MUDAR ESTADO BOLSEIROS']);
        $permissao12 = Permission::create(['name' => 'LISTAR TIPO DE BOLSA']);
        $permissao13 = Permission::create(['name' => 'LISTAR INSTITUICAO']);
        
        $permissao14 = Permission::create(['name' => 'LISTAR INSTITUICAO COM DESPESA']);
        $permissao15 = Permission::create(['name' => 'LISTAR INSTITUICAO COM RECEITA']);

        $permissao16 = Permission::create(['name' => 'CRIAR DESCONTO']);
        $permissao16 = Permission::create(['name' => 'EDITAR DESCONTO']);
        $permissao16 = Permission::create(['name' => 'ELIMINAR DESCONTO']);
        $permissao16 = Permission::create(['name' => 'ATRIBUIR DESCONTO']);
        $permissao162 = Permission::create(['name' => 'EDITAR ATRIBUICAO DESCONTO']);
        $permissao161 = Permission::create(['name' => 'ACTUALIZAR ATRIBUICAO DESCONTO']);
        
        $permissao17 = Permission::create(['name' => 'LISTAR DESCONTO']);
        $permissao18 = Permission::create(['name' => 'FECHO DO CAIXA DIARIO']);
        $permissao19 = Permission::create(['name' => 'FECHO DO CAIXA GERAL']);
        $permissao20 = Permission::create(['name' => 'FECHO DO CAIXA POR UTILIZADOR']);
        $permissao21 = Permission::create(['name' => 'LISTAR ESTUDANTES COM DESCONTOS']);
        $permissao22 = Permission::create(['name' => 'LISTAR ESTUDANTES COM CREDITO INSTITUICIONAL']);
        
        $permissao23 = Permission::create(['name' => 'LISTAR ESTUDANTES ISENTO']);
        $permissao24 = Permission::create(['name' => 'LISTAR PERFIL']);
        $permissao25 = Permission::create(['name' => 'LISTAR PERMISSOES']);
        $permissao26 = Permission::create(['name' => 'LISTAR UTILIZADORES']);
        $permissao27 = Permission::create(['name' => 'ESTUDANTES COM MENSALIDADES PAGAS']);
        $permissao28 = Permission::create(['name' => 'ESTUDANTES DEVEDORES']);
        $permissao29 = Permission::create(['name' => 'ATRIBUIR BOLSAS']);
        $permissao30 = Permission::create(['name' => 'EDITAR ATRIBUICAO BOLSAS']);
        $permissao31 = Permission::create(['name' => 'ACTUALIZAR ATRIBUICAO BOLSAS']);
        
        $permissao96 = Permission::create(['name' => 'CRIAR BOLSAS']);
        $permissao969 = Permission::create(['name' => 'EDITAR BOLSAS']);
        $permissao567 = Permission::create(['name' => 'ELIMINAR BOLSAS']);
        $permissao562 = Permission::create(['name' => 'VISUALIZAR BOLSAS']);
        
        $permissao196 = Permission::create(['name' => 'CRIAR INSTITUICOES']);
        $permissao1969 = Permission::create(['name' => 'EDITAR INSTITUICOES']);
        $permissao1567 = Permission::create(['name' => 'ELIMINAR INSTITUICOES']);
        $permissao1567 = Permission::create(['name' => 'ASSOCIAR BOLSA INSTITUICOES']);
        $permissao1562 = Permission::create(['name' => 'VISUALIZAR INSTITUICOES']);
        
        $actualiza_creadencias = Permission::create(['name' => 'ACTUALIZAR CREDENCIAS']);
        $actualizar_dados_pessoais = Permission::create(['name' => 'ACTUALIZAR DADOS PESSAIS']);
        
        
        $listar_tipos_estudantes = Permission::create(['name' => 'LISTAR TIPOS ESTUDANTES']);
        $pesquisa_geral_estudantes1 = Permission::create(['name' => 'PESQUISA GERAL DE ESTUDANTES']);
        $pesquisa_geral_estudantes2 = Permission::create(['name' => 'ISENTAR PAGAMENTOS']);
        $pesquisa_geral_estudantes3 = Permission::create(['name' => 'REMOVER ISENTAR PAGAMENTOS']);
        $pesquisa_geral_estudantes4 = Permission::create(['name' => 'ACTIVAR ISENTAR PAGAMENTOS']);
        $pesquisa_geral_estudantes5 = Permission::create(['name' => 'ISENTAR MULTAS']);
        $pesquisa_geral_estudantes6 = Permission::create(['name' => 'REMOVER ISENTAR MULTAS']);
        $pesquisa_geral_estudantes7 = Permission::create(['name' => 'SERVICO EMONUMENTOS']);
        $pesquisa_geral_estudantes8 = Permission::create(['name' => 'VISUALIZAR GRAFICOS FINANCEIRO']);
        $pesquisa_geral_estudantes9 = Permission::create(['name' => 'ACTUALIZAR SALDOS']);
        
        
        $pesquisa_geral_estudantes89 = Permission::create(['name' => 'LISTAR ESTUDANTES DIPLOMADOS']);
        $pesquisa_geral_estudantes839 = Permission::create(['name' => 'LISTAR ESTUDANTE COM DESCONTO']);
        $listar_log_acesso = Permission::create(['name' => 'LISTAGEM LOGS ACESSO']);
        $visualizar_dashboard = Permission::create(['name' => 'VISUALIZAR DASHBOARD']);
        
        
        $administrador = Role::create(['name' => 'ADMINISTRADOR'])->syncPermissions([
            $permissao1,
            $permissao2,
            $permissao3,
            $permissao4,
            $permissao5,
            $permissao6,
            $permissao7,
            $permissao8,
            $permissao9,
            $permissao10,
            $permissao11,
            $permissao12,
            $permissao13,
            $permissao14,
            $permissao15,
            $permissao16,
            $permissao17,
            $permissao18,
            $permissao19,
            $permissao20,
            $permissao21,
            $permissao22,
            $visualizar_dashboard,
            $permissao23,
            $permissao24,
            $permissao25,
            $permissao26,
            $permissao27,
            $permissao28,
            $permissao29,
            $actualiza_creadencias,
            $actualizar_dados_pessoais,
            $listar_tipos_estudantes
        ]);
  
        $operador= Role::create(['name' => 'OPERADORES'])->syncPermissions([
            $permissao1,
            $actualiza_creadencias,
            $actualizar_dados_pessoais
        ]);
    }
}
