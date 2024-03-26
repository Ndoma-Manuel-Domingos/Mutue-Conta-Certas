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
                    
                    <div class="col-12 col-md-3 mb-4">
                      <label for="tipo" class="form-label">Tipos</label>
                      <Select2 v-model="form.tipo"
                        id="tipo" class="col-12 col-md-12"
                        :options="tipos" :settings="{ width: '100%' }" 
                        @select="getSubContas($event)"
                      />
                      <span class="text-danger" v-if="form.errors && form.errors.tipo">{{ form.errors.tipo }}</span>
                    </div>

                    <div class="col-12 col-md-3 mb-4">
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
                  <table class="table table-bordered table-hover" id="tabela_de_contas_relacionadas">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Conta</th>
                        <th>Tipo</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in subcontas" :key="item">
                        <td>#</td>
                        <td>{{ item.numero }}</td>
                        <td>{{ item.designacao }}</td>
                        <td>{{ item.tipo }}</td>
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
  props: ["contas", "subcontas"],
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
      
      subcontas: [
      ],
      
      tipos: [
        {'id': "R", 'text': "Geral"},
        {'id': "M", 'text': "Movimento"},
        {'id': "I", 'text': "Intregadora"},
      ],

      form: {
        conta_id: "",
        designacao: "",
        numero: "",
        tipo: "",
        estado: "activo",
      },
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

          this.getSubContas();
          
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Dados Salvos com Sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });
          
          // window.location.reload();
          // console.log("Resposta da requisição POST:", response.data);
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
    