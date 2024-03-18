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
          <li
            class="nav-item"
            title="EMPRESAS"
          >
            <Link
              href="/empresas"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Empresas/Index',
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
          
          <li
            class="nav-item"
            title="PGC"
          >
            <Link
              href="/plano-geral-contas"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'PlanoGeralConta/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>PGC</p>
            </Link>
          </li>
        
        
          <li
            class="nav-item"
            title="CLASSES"
          >
            <Link
              href="/classes"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Classes/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Classes</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="CONTAS"
          >
            <Link
              href="/contas"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Contas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Contas</p>
            </Link>
          </li>

          <li
            class="nav-item"
            title="SUBCONTAS"
          >
            <Link
              href="/sub-contas"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'SubContas/Index',
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
          <i class="nav-icon fas fa-cog"></i>
          <p>
            Operações
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li
            class="nav-item"
            title="DIÁRIOS"
          >
            <Link
              href="/diarios"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Diarios/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Diários</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="TIPOS DE DOCUMENTOS"
          >
            <Link
              href="/tipos-documentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'TipoDocumentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Tipos de Documentos</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="Movimentos"
          >
            <Link
              href="/movimentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Movimentos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Movimentos</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="Movimentos"
          >
            <Link
              href="/movimentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Contas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Apuramento de Resultados</p>
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
          <li
            class="nav-item"
            title="CLASSES"
          >
            <Link
              href="/diarios"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Classes/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Balanços</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="Balancetes"
          >
            <Link
              href="/balancetes"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'SubContas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Balancetes</p>
            </Link>
          </li>
          
          <!-- <li
            class="nav-item"
            title="Movimentos"
          >
            <Link
              href="/movimentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Contas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Balacentes</p>
            </Link>
          </li> -->

          <li
            class="nav-item"
            title="Balancetes"
          >
            
      <li class="nav-item">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
        <p>
        <i class="nav-icon fas fa-credit-card"></i>
          Extratos</p>
          <!-- Gestão de relatorios  -->
            <i class="right fas fa-angle-left"></i>
        </a>
        <ul class="nav nav-treeview">
          <li
            class="nav-item"
            title="CLASSES"
          >
            <Link
              href="/diarios"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Classes/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Diário</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="Movimentos"
          >
            <Link
              href="/movimentos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Contas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Semanal</p>
            </Link>
          </li>

          <li
            class="nav-item"
            title="Balancetes"
          >
            <Link
              href="/balancetes"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'SubContas/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Anual</p>
            </Link>
          </li>


        </ul>
      </li>
           
          </li>


        </ul>
      </li>
      
      <li class="nav-item" v-if="sessions">
        <a
          href="#"
          class="nav-link"
          :class="{ active: $page.component.startsWith('AreaFinanceira/') }"
        >
          <i class="nav-icon fas fa-cog"></i>
          <p>
            Gestão
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li
            class="nav-item"
            title="Exercicios"
          >
            <Link
              href="/exercicios"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Exercicios/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Exercícios</p>
            </Link>
          </li>
          
          <li
            class="nav-item"
            title="Periodos"
          >
            <Link
              href="/periodos"
              class="nav-link"
              :class="{
                active:
                  $page.component == 'Periodos/Index',
              }"
            >
              <i class="far fa-circle nav-icon"></i>
              <p>Períodos</p>
            </Link>
          </li>

        </ul>
      </li>

      <!-- <div class="ml-auto">
        <ul class="navbar-nav">
          <li class="nav-item text-left">
            <Link
              class="nav-link btn btn-link btn-danger text-white"
              href="/logout"
              method="post"
              as="button"
              type="button"
            >
              <i class="fas fa-sign-out-alt"></i>
              Sair
            </Link>
          </li>
        </ul>
      </div> -->
    </ul>
  </nav>
</template>

<script>

import { Link } from "@inertiajs/inertia-vue3";
export default {
  
  components: {
    Link
  },

  data: () => ({
  }),

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
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
