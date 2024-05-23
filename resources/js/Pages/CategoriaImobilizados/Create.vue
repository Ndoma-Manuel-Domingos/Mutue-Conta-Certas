<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR CATEGORIA DE IMOBILIZADOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/categorias-imobilizados">Listagem</a>
              </li>
              <li class="breadcrumb-item active">
                Criar Categoria de Imobilizado
              </li>
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
                      <label for="designacao" class="form-label"
                        >Designação</label
                      >
                      <input
                        type="text"
                        id="designacao"
                        v-model="form.designacao"
                        class="form-control"
                        placeholder="Designação"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.designacao"
                        >{{ form.errors.designacao }}</span
                      >
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                      <label for="sigla" class="form-label"
                        >Sigla</label
                      >
                      <input
                        type="text"
                        id="sigla"
                        v-model="form.sigla"
                        class="form-control"
                        placeholder="Sigla"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.sigla"
                        >{{ form.errors.sigla }}</span
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

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                  id="tabela_de_diarias"
                >
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Designação</th>
                      <th>Sigla</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in categorias" :key="item">
                      <td>{{ item.id }}</td>
                      <td>{{ item.designacao }}</td>
                      <td>{{ item.sigla }}</td>
                      <td>
                        <div class="float-right">
                          <a
                            :href="`/categorias-imobilizados/${item.id}/edit`"
                            class="btn btn-sm btn-success"
                            ><i class="fas fa-edit"></i> Editar</a
                          >
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
    
<script>
export default {
  props: ["categorias"],
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
  data() {
    return {
      estados: [
        { id: "activo", text: "Activo" },
        { id: "desactivo", text: "Desactivo" },
      ],

      form: {
        designacao: "",
      },
    };
  },
  mounted() {},
  methods: {
    submit() {
      this.$Progress.start();

      axios
        .post(route("categorias-imobilizados.store"), this.form)
        .then((response) => {
          // this.form.reset();
          this.$Progress.finish();

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
          console.log("Resposta da requisição POST:", response.data);
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
            timer: 4000,
          });

          console.error("Erro ao fazer requisição POST:", error);
        });
    },
  },
};
</script>
    