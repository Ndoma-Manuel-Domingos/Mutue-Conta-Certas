<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">CRIAR SUBCONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/sub-contas">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Criar subContas</li>
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
                      <label for="conta_id" class="form-label">Contas</label>
                      <Select2
                        v-model="form.conta_id"
                        id="conta_id"
                        class="col-12 col-md-12"
                        :options="contas"
                        :settings="{ width: '100%' }"
                        @select="getSubContas($event)"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.conta_id"
                        >{{ form.errors.conta_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="numero" class="form-label"
                        >Código da SubConta</label
                      >
                      <input
                        type="text"
                        id="numero"
                        v-model="form.numero"
                        class="form-control"
                        placeholder="Ex: 1.1, 1.1.1"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.numero"
                        >{{ form.errors.numero }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-6 mb-4">
                      <label for="designacao" class="form-label"
                        >Designação</label
                      >
                      <input
                        type="text"
                        id="designacao"
                        v-model="form.designacao"
                        class="form-control"
                        placeholder="Informe o designação da subconta:"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.designacao"
                        >{{ form.errors.designacao }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="estado" class="form-label">Estado</label>
                      <Select2
                        v-model="form.estado"
                        id="estado"
                        class="col-12 col-md-12"
                        :options="estados"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.estado"
                        >{{ form.errors.estado }}</span
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
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Conta</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in subcontas" :key="item">
                        <td>#</td>
                        <td>{{ item.numero }}</td>
                        <td>{{ item.designacao }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ subcontas.total }}</Link
                >
                <Paginacao
                  :links="subcontas.links"
                  :prev="subcontas.prev_page_url"
                  :next="subcontas.next_page_url"
                />
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
  props: ["contas", "subcontas"],
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
  },
  data() {
    return {
      estados: [
        { id: "activo", text: "Activo" },
        { id: "desactivo", text: "Desactivo" },
      ],
      
      subcontas: [
      ],

      form: {
        conta_id: "",
        designacao: "",
        numero: "",
        estado: "",
      },
    };
  },
  mounted() {
   
  },
  methods: {
    getSubContas({ id, text }) {
      axios
        .get(`/get-conta/${this.form.conta_id}`)
        .then((response) => {
          console.log(response.data);
          this.form.numero = response.data.conta.numero + ".";
          this.subcontas = [];
          this.subcontas = response.data.subcontas;
        })
        .catch((error) => {});
    },

    submit() {
      this.$Progress.start();

      axios
        .post(route("sub-contas.store"), this.form)
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
    