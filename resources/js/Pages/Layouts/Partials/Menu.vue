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
          <i class="nav-icon fas fa-chart-line"></i>
          <p>
            PGC - Plano Geral
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
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
