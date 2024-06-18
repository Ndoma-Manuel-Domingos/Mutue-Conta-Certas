<template>
  <MainLayoutsAdmin>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR OPERADORES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/operadores-admin">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Criae Operador</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">
            <form @submit.prevent="submit">
              <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                      <label for="nome" class="form-label">Nome</label>
                      <input
                        type="text"
                        id="nome"
                        placeholder="Informe o Nome"
                        v-model="form.nome"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.nome"
                        >{{ form.errors.nome }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="email" class="form-label"
                        >E-mail</label
                      >
                      <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        placeholder="Informe o E-mail"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.email"
                        >{{ form.errors.email }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-6 mb-4">
                      <label for="password" class="form-label"
                        >Senha</label
                      >
                      <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        placeholder="Informe o E-mail"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.password"
                        >{{ form.errors.password }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-6 mb-4">
                      <label for="confirmed" class="form-label"
                        >Confirmar Senha</label
                      >
                      <input
                        type="password"
                        id="confirm_password"
                        v-model="form.confirm_password"
                        placeholder="Informe o E-mail"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.confirm_password"
                        >{{ form.errors.confirm_password }}</span
                      >
                    </div>
                   
                  </div>
                </div>

                <div class="card-footer">
                  <button class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
  </MainLayoutsAdmin>
</template>

<script>

import MainLayoutsAdmin from '../../Layouts/MainLayoutsAdmin.vue';
    

export default {
  props: ["classes"],
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
  },
  components: { MainLayoutsAdmin },
  data() {
    return {

      form: this.$inertia.form({
        nome: "",
        email: "",
        password: "12345678",
        confirm_password: "12345678",
      }),
      
    };
  },
  mounted() {},
  methods: {
    submit() {
      this.$Progress.start();

      this.form.post(route("operadores-admin.store"), {
        preverseScroll: true,
        onSuccess: () => {
          this.form.reset();

          Swal.fire({
            toast: true,
            icon: "success",
            title: "Dados Salvos com Sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          window.location.reload();

          this.$Progress.finish();
        },
        onError: (errors) => {
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Impossível salvar o operador!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          this.$Progress.fail();

          console.error("Erro ao fazer requisição POST:", errors);
        },
      });
    },
  },
};
</script>