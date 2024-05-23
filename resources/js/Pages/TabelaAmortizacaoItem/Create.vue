<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR ITEM AMORTIZAÇÃO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/tabela-amortizacoes-items">Listagem</a>
              </li>
              <li class="breadcrumb-item active">
                Criar Amortização
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
                      <label for="amortizacao_id" class="form-label">Amortização</label>
                      <Select2 v-model="form.amortizacao_id"
                        id="amortizacao_id" class="col-12 col-md-12"
                        :options="amortizacoes" :settings="{ width: '100%' }" 
                        @select="getItems($event)"
                      />
                      <span class="text-danger" v-if="form.errors && form.errors.amortizacao_id">{{ form.errors.amortizacao_id }}</span>
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
                        placeholder="Designação"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.designacao"
                        >{{ form.errors.designacao }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-6 mb-4">
                      <label for="taxa" class="form-label"
                        >Taxa %</label
                      >
                      <input
                        type="text"
                        id="taxa"
                        v-model="form.taxa"
                        class="form-control"
                        placeholder="Taxa %"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.taxa"
                        >{{ form.errors.taxa }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-6 mb-4">
                      <label for="vida_util" class="form-label"
                        >Vida Útil Anos</label
                      >
                      <input
                        type="text"
                        id="vida_util"
                        v-model="form.vida_util"
                        class="form-control"
                        placeholder="Vida Útil Anos do Produto"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.vida_util"
                        >{{ form.errors.vida_util }}</span
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
                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Designação</th>
                      <th>Taxa %</th>
                      <th>Vida Útil Anos</th>
                      <th>Amortizição</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in items" :key="item">
                      <td>{{ item.id }}</td>
                      <td>{{ item.designacao }}</td>
                      <td>{{ item.taxa }}</td>
                      <td>{{ item.vida_util }}</td>
                      <td>{{ item.amortizacao.ordem }} - {{ item.amortizacao.designacao }}</td>
                      <td>
                        <div class="float-right">
                          <a
                            :href="`/tabela-amortizacoes-items/${item.id}/edit`"
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
  props: [
    "amortizacoes"
  ],
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
        amortizacao_id: "",
        designacao: "",
        taxa: "",
        vida_util: "",
      },
                  
      items: [ ],
    };
  },
  mounted() {},
  methods: {
  
    getItems() {
      axios
        .get(`/get-items-tabela-amortizacoes/${this.form.amortizacao_id}`)
        .then((response) => {
          this.items = [];
          this.items = response.data.items;
        })
        .catch((error) => {});
    },
  
    submit() {
      this.$Progress.start();

      axios
        .post(route("tabela-amortizacoes-items.store"), this.form)
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
    