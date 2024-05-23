<template>
  <nav class="mt-2">
    <ul
      class="nav nav-pills nav-sidebar flex-column"
      data-widget="treeview"
      role="menu"
      data-accordion="false"
    >
      <li class="nav-item">
        <Link
          href="/dashboard"
          class="nav-link"
          :class="{ active: $page.component == 'Dashboard' }"
        >
          <i class="nav-icon fas fa-home"></i>
          <p>Dashboard</p>
        </Link>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-cog"></i>
          <p>
            Empresas
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="EMPRESAS">
            <Link
              href="/empresas"
              class="nav-link"
              :class="{
                active: $page.component == 'Empresas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listagem</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
            PGC - Plano Geral
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="PGC">
            <Link
              href="/plano-geral-contas"
              class="nav-link"
              :class="{
                active: $page.component == 'PlanoGeralConta/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>PGC</p>
            </Link>
          </li>

          <li class="nav-item" title="CLASSES">
            <Link
              href="/classes"
              class="nav-link"
              :class="{
                active: $page.component == 'Classes/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Classes</p>
            </Link>
          </li>

          <li class="nav-item" title="CONTAS">
            <Link
              href="/contas"
              class="nav-link"
              :class="{
                active: $page.component == 'Contas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Contas</p>
            </Link>
          </li>

          <li class="nav-item" title="SUBCONTAS">
            <Link
              href="/sub-contas"
              class="nav-link"
              :class="{
                active: $page.component == 'SubContas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Sub-Contas</p>
            </Link>
          </li>
        </ul>
      </li>
      
      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-cogs"></i>
          <p>
            Amortização
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="IMOBILIZADOS">
            <Link
              href="/imobilizados"
              class="nav-link"
              :class="{
                active: $page.component == 'Imobilizados/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Imobilizados</p>
            </Link>
          </li>
          
          <li class="nav-item" title="MAPA DE AMORTIZAÇÃO">
            <Link
              href="/mapa-amortizacoes"
              class="nav-link"
              :class="{
                active: $page.component == 'Imobilizados/MapaAmortizacao',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Mapa de Amortização</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-cog"></i>
          <p>
            Operações
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
       
          <li class="nav-item" title="DIÁRIOS">
            <Link
              href="/diarios"
              class="nav-link"
              :class="{
                active: $page.component == 'Diarios/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Diários</p>
            </Link>
          </li>

          <li class="nav-item" title="TIPOS DE DOCUMENTOS">
            <Link
              href="/tipos-documentos"
              class="nav-link"
              :class="{
                active: $page.component == 'TipoDocumentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Documentos</p>
            </Link>
          </li>

          <li class="nav-item" title="Movimentos">
            <Link
              href="/movimentos"
              class="nav-link"
              :class="{
                active: $page.component == 'Movimentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Movimentos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="Fluxo de Caixa">
            <Link
              href="/fluxos-caixas"
              class="nav-link"
              :class="{
                active: $page.component == 'FluxoCaixa/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Fluxo de Caixa</p>
            </Link>
          </li>
          
          <li class="nav-item" title="Demonstração de Fluxo de Caixa">
            <Link
              href="/demonstracao-fluxo-caixa"
              class="nav-link"
              :class="{
                active: $page.component == 'FluxoCaixa/Demonstracao',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Demonstração de Fluxo de Caixa</p>
            </Link>
          </li>

          <li class="nav-item" title="Apuramento de Resultados">
            <Link
              href="/apuramento-resultados"
              class="nav-link"
              :class="{
                active: $page.component == 'ApuramentoResultado/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Apuramento de Resultados</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item" v-if="sessions">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-file-pdf"></i>
          <p>
            <i class="right fas fa-angle-left"></i>
            Gestão
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="Exercicios">
            <Link
              href="/exercicios"
              class="nav-link"
              :class="{
                active: $page.component == 'Exercicios/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Exercícios</p>
            </Link>
          </li>

          <li class="nav-item" title="Periodos">
            <Link
              href="/periodos"
              class="nav-link"
              :class="{
                active: $page.component == 'Periodos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Períodos</p>
            </Link>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-credit-card"></i>
          <p>
            Gestão de Relatorios
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="BALANÇO">
            <Link
              href="/balancos"
              class="nav-link"
              :class="{
                active: $page.component == 'Balancos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Balanço</p>
            </Link>
          </li>

          <li class="nav-item" title="BALANCETE">
            <Link
              href="/balancetes"
              class="nav-link"
              :class="{
                active: $page.component == 'Balancetes/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Balancete</p>
            </Link>
          </li>
          
          <li class="nav-item" title="Extrato de contas">
            <Link
              href="/extratos-contas"
              class="nav-link"
              :class="{
                active: $page.component == 'ExtratoContas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Extrato de Conta</p>
            </Link>
          </li>
        
        </ul>
      </li>

      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-cog"></i>
          <p>
            Tabelas de Apoio
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
        
          <li class="nav-item" title="CATEGORIAS DE IMOBILIZADOS">
            <Link
              href="/categorias-imobilizados"
              class="nav-link"
              :class="{
                active: $page.component == 'CategoriaImobilizados/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Categorias de Imobilizados</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TABELA DE AMORITIZAÇÃO">
            <Link
              href="/tabela-amortizacoes"
              class="nav-link"
              :class="{
                active: $page.component == 'TabelaAmortizacao/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tabela Amortização</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TABELA DE ITEMS DE AMORITIZAÇÃO">
            <Link
              href="/tabela-amortizacoes-items"
              class="nav-link"
              :class="{
                active: $page.component == 'TabelaAmortizacaoItem/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tabela Amortização Itens</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TIPOS DE CRÉDITOS">
            <Link
              href="/tipos-creditos"
              class="nav-link"
              :class="{
                active: $page.component == 'TipoCreditos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Créditos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="CONTRAPARTIDAS">
            
            <Link
              href="/contrapartidas"
              class="nav-link"
              :class="{
                active: $page.component == 'Contrapartidas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Contrapartidas</p>
            </Link>
          </li>
          
          <li class="nav-item" title="CENTRO DE CUSTO">
            <Link
              href="/centro_de_custo"
              class="nav-link"
              :class="{
                active: $page.component == 'CentroDeCusto/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Centro de Custos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TIPOS DE MOVIMENTOS">
            <Link
              href="/tipos-movimentos"
              class="nav-link"
              :class="{
                active: $page.component == 'TipoMovimentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Movimentos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="DOCUMENTOS">
            <Link
              href="/documentos"
              class="nav-link"
              :class="{
                active: $page.component == 'Documentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Documentos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TIPOS DE PROVEITOS">
            <Link
              href="/tipos-proveitos"
              class="nav-link"
              :class="{
                active: $page.component == 'TipoProveitos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Proveitos</p>
            </Link>
          </li>
          
          <li class="nav-item" title="REGIME DE EMPRESAS">
            <Link
              href="/regime-empresa"
              class="nav-link"
              :class="{
                active: $page.component == 'Regime/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Regime de Empresa</p>
            </Link>
          </li>
          
          <li class="nav-item" title="TIPOS DE EMPRESAS">
            <Link
              href="/tipos-empresas"
              class="nav-link"
              :class="{
                active: $page.component == 'TipoEmpresas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Empresas</p>
            </Link>
          </li>
          
          <li class="nav-item" title="GRUPOS DE EMPRESAS">
            <Link
              href="/grupos-empresas"
              class="nav-link"
              :class="{
                active: $page.component == 'GrupoEmpresas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Grupos de Empresas</p>
            </Link>
          </li>

          <li class="nav-item" title="MOEDAS">
            <Link
              href="/moeda"
              class="nav-link"
              :class="{
                active: $page.component == 'Moeda/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Moedas de Operação</p>
            </Link>
          </li> 
          
          <li class="nav-item" title="PAISES">
            <Link
              href="/paises"
              class="nav-link"
              :class="{
                active: $page.component == 'Paises/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Países</p>
            </Link>
          </li>

          <li class="nav-item" title="PROVINCIA">
            <Link
              href="/provincia"
              class="nav-link"
              :class="{
                active: $page.component == 'Provincia/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Provincias</p>
            </Link>
          </li>

          <li class="nav-item" title="MUNICIPIO">
            <Link
              href="/municipio"
              class="nav-link"
              :class="{
                active: $page.component == 'Municipio/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Municípios</p>
            </Link>
          </li>
          
          <li class="nav-item" title="COMUNAS">
            <Link
              href="/comuna"
              class="nav-link"
              :class="{
                active: $page.component == 'Comuna/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Nova Comuna</p>
            </Link>
          </li>
        </ul>
      </li>
      
      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-users"></i>
          <p>
            Operadores
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" title="Operadores">
            <Link
              href="/operadores"
              class="nav-link"
              :class="{
                active: $page.component == 'Operadores/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Listagem</p>
            </Link>
          </li>
        </ul>
      </li>

      
    </ul>
  </nav>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
  components: {
    Link,
  },

  data: () => ({}),

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
    sessions_exercicio() {
      return this.$page.props.sessions.empresa_sessao;
    },
  },
};
</script>


<style>
.nav-pills .nav-link.active,
.nav-pills .show > .nav-link {
  color: #fff;
  background-color: #52c7ed;
}
</style>
