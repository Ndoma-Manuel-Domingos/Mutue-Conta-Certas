<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR CONTRAPARTIDA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/contrapartidas">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Criar Contrapartida</li>
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
                      <label for="tipo_credito_id" class="form-label"
                        >Tipos de Créditos</label
                      >
                      <Select2
                        v-model="form.tipo_credito_id"
                        id="tipo_credito_id"
                        class="col-12 col-md-12"
                        :options="tipos_creditos"
                        :settings="{ width: '100%' }"
                        @select="getSubContas($event)"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.tipo_credito_id"
                        >{{ form.errors.tipo_credito_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="sub_conta_id" class="form-label"
                        >Subcontas</label
                      >
                      <Select2
                        v-model="form.sub_conta_id"
                        id="sub_conta_id"
                        class="col-12 col-md-12"
                        :options="sub_contas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.sub_conta_id"
                        >{{ form.errors.sub_conta_id }}</span
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

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header"></div>
              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                  id="tabela_de_contas_relacionadas"
                >
                  <thead>
                    <tr>
                      <th>Número</th>
                      <th>Subconta</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in subcontas" :key="item">
                      <td>{{ item.sub_conta.numero }}</td>
                      <td>{{ item.sub_conta.designacao }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="card-footer"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
    
<script>
export default {
  props: ["tipos_creditos", "sub_contas"],
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
      form: {
        sub_conta_id: "",
        tipo_credito_id: "",
      },
      
      subcontas: [
      ],
    };
  },
  mounted() {
    $('#tabela_de_contas_relacionadas').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    });
  },
  methods: {
    getSubContas() {
      axios
        .get(`/get-subconta/${this.form.tipo_credito_id}`)
        .then((response) => {
          this.subcontas = [];
          this.subcontas = response.data.subcontas;
        })
        .catch((error) => {});
    },

    submit() {
      this.$Progress.start();

      axios
        .post(route("contrapartidas.store"), this.form)
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
    