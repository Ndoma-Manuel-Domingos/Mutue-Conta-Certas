<template>
  <div class="hold-transition sidebar-mini">
    <div class="wrapper">
      <vue-progress-bar></vue-progress-bar>

      <nav class="main-header class= navbar navbar-expand navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"
              ><i class="fas fa-bars"></i
            ></a>
          </li>
        </ul>

        <div class="mx-auto">
          <div v-if="sessions">
            <h5 class="text-danger text-uppercase">EMPRESA ACTIVA:{{ sessions.nome_empresa ?? '' }}<span v-if="sessions_exercicio"> - ({{ sessions_exercicio.designacao ?? '' }})</span> </h5>
          </div>
        </div>

        <div class="ml-auto">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link h4" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
              </a>
              <div
                class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center"
              >
                <div class="bg-info">
                  <img
                    src="~admin-lte/dist/img/user2-160x160.jpg"
                    alt="User Avatar"
                    class="img-size-64 ml-auto img-circle m-4"
                    style="text-align: center"
                  />
                </div>
                <div>
                  <div class="dropdown-divider"></div>
                  <a href="/privacidade" class="dropdown-item">
                    <i class="fas fa-lock mr-2"></i>
                    <span>Alterar Password</span>
                    <!-- <span class="float-right text-muted text-sm">2 days</span> -->
                  </a>

                  <div class="dropdown-divider"></div>
                  <a href="/privacidade/meus-dados" class="dropdown-item">
                    <i class="fas fa-user-edit mr-2"></i>
                    <span>Actualizar Dados</span>
                    <!-- <span class="float-right text-muted text-sm">2 days</span> -->
                  </a>
                  
                  <div v-if="sessions"> 
                    <div class="dropdown-divider"></div>
                    <Link
                      @click="logout_empresa"
                      class="dropdown-item text-danger"
                      type="button"
                      method="post"
                      as="button"
                    >
                      <i class="fas fa-sign-out-alt mr-2"></i
                      ><span>Terminar sessão Empresa</span>
                    </Link>
                  </div>

                  <div class="dropdown-divider"></div>
                  <Link
                    @click="logout"
                    class="dropdown-item text-danger"
                    type="button"
                    method="post"
                    as="button"
                  >
                    <i class="fas fa-sign-out-alt mr-2"></i
                    ><span>Terminar sessão</span>
                  </Link>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <aside class="main-sidebar sidebar-dark-primary elevation-1">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
          <img
            src="images/logotipo_contas_centas.png"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.8"
          />
          <span class="brand-text font-weight-light">M-CONTAS CERTAS</span>
        </a>

        <div class="sidebar">
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img
                src="~admin-lte/dist/img/user2-160x160.jpg"
                class="img-circle elevation-2"
                alt="User Image"
              />
            </div>
            <div class="info">
              <a href="#" class="d-block">{{ user.name }}</a>
            </div>
          </div>

          <Menu />
        </div>
      </aside>

      <div class="content-wrapper">
        <slot />
      </div>
      <!-- <Footer /> -->
    </div>
  </div>
</template>

<script>
import Menu from "./Partials/Menu.vue";

export default {
  components: {
    Menu,
  },

  data() {
    return {};
  },

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
    sessions_exercicio() {
      return this.$page.props.sessions.exercicio_sessao;
    },

  },
  
  mounted() {
    // $('.select2').select2()
  },
  
  methods: {
    logout() {
      this.$Progress.start();

      axios.post(route('mf.logout'))
        .then((response) => {
          this.$Progress.finish();
          
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Volte sempres!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
          window.location.reload();
        })
        .catch((error) => {
          // sweetError("Ocorreu um erro ao actualizar Instituição!");
          this.$Progress.fail();
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Correu um erro ao salvar os dados!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
        });
    },
    
    logout_empresa() {
      this.$Progress.start();

      axios.post(route('mf.logout_empresa'))
        .then((response) => {
          this.$Progress.finish();
          
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Volte sempres!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
          window.location.reload();
        })
        .catch((error) => {
          // sweetError("Ocorreu um erro ao actualizar Instituição!");
          this.$Progress.fail();
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Correu um erro ao salvar os dados!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
        });
    },
  },
};
</script>

<style>
/* .select2-container--default .select2-selection--single{
    background-color: brown;
  } */

.select2-container--default .select2-selection--single {
  background-color: #ffffff;
  padding: 10px 10px 35px 10px;
  border: 1px solid #000000;
}

.form-control {
  padding: 23px 10px 22px 10px;
  font-size: 12pt;
  border: 1px solid #9b9b9b;
}
</style>