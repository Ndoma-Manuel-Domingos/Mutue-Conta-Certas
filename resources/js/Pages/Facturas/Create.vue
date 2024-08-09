<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR FACTURAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/facturas">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Criar Facturas</li>
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
                    <div class="col-12 col-md-3 mb-4">
                      <label for="exercicio_id" class="form-label"
                        >Exercício</label
                      >
                      <Select2
                        v-model="form.exercicio_id"
                        id="exercicio_id"
                        :options="exercicios"
                        :settings="{ width: '100%' }"
                        @select="getPeriodos($event)"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.exercicio_id"
                        >{{ form.errors.exercicio_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="periodo_id" class="form-label">Período</label>
                      <Select2
                        v-model="form.periodo_id"
                        id="periodo_id"
                        :options="lista_periodos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.periodo_id"
                        >{{ form.errors.periodo_id }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                      <label for="referencia_documento" class="form-label"
                        >Referência do Documento</label
                      >
                      <input
                        type="text"
                        id="referencia_documento"
                        v-model="form.referencia_documento"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.referencia_documento"
                        >{{ form.errors.referencia_documento }}</span
                      >
                    </div>
            

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_movimento" class="form-label"
                        >Data Emissão</label
                      >
                      <input
                        type="date"
                        id="data_movimento"
                        v-model="form.data_movimento"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.data_movimento"
                        >{{ form.errors.data_movimento }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_vencimento" class="form-label"
                        >Data Vencimento</label
                      >
                      <input
                        type="date"
                        id="data_vencimento"
                        v-model="form.data_vencimento"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.data_vencimento"
                        >{{ form.errors.data_vencimento }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="tipo_documento_id" class="form-label">Tipos de Documentos</label>
                      <Select2
                        v-model="form.tipo_documento_id"
                        id="tipo_documento_id"
                        :options="documentos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.tipo_documento_id"
                        >{{ form.errors.tipo_documento_id }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                      <label for="cliente_id" class="form-label">Clientes</label>
                      <Select2
                        v-model="form.cliente_id"
                        id="cliente_id"
                        :options="subcontas_clientes"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.cliente_id"
                        >{{ form.errors.cliente_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="fornecedor_id" class="form-label">Fornecedores</label>
                      <Select2
                        v-model="form.fornecedor_id"
                        id="fornecedor_id"
                        :options="subcontas_fornecedores"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.fornecedor_id"
                        >{{ form.errors.fornecedor_id }}</span
                      >
                    </div>
                       

                    <div class="col-12 col-md-2 mb-4">
                      <label for="centro_custo_id" class="form-label">Centro de Custos</label>
                      <Select2
                        v-model="form.centro_custo_id"
                        id="centro_custo_id"
                        :options="centro_custos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.centro_custo_id"
                        >{{ form.errors.centro_custo_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="status" class="form-label">Estados</label>
                      <Select2
                        v-model="form.status"
                        id="status"
                        :options="estados"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.status"
                        >{{ form.errors.status }}</span
                      >
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                      <label for="valor_total" class="form-label"
                        >Valor Total</label
                      >
                      <input
                        type="text"
                        id="valor_total"
                        v-model="form.valor_total"
                        class="form-control"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.valor_total"
                        >{{ form.errors.valor_total }}</span
                      >
                    </div>

            
                    <div class="col-12 col-md-12 mb-4">
                      <label for="sub_conta_id" class="form-label"
                        >Descrição</label
                      >
                      <textarea
                        v-model="form.descricao"
                        class="form-control"
                        id=""
                        cols="30"
                        rows="2"
                      ></textarea>
                    </div>
                  <!-- //////////////////////////////////// -->

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
    
    
  </MainLayouts>
</template>
    
<script>
export default {
  props: [
    "documentos",
    "subcontas_clientes",
    "subcontas_fornecedores",
    "exercicios",
    "periodos",
    "centro_custos",
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
    periodo_sessao() {
      return this.$page.props.sessions.periodo_sessao;
    },
  },
  data() {
    return {
      estados: [
        { id: "Pendente", text: "Pendente" },
        { id: "Pago", text: "Pago" },
        { id: "Em atraso", text: "Em atraso" },
      ],

      lista_periodos: [],

      form: this.$inertia.form({
        
        exercicio_id: "",
        periodo_id: "",
        referencia_documento: "",
        data_vencimento: "",
        data_movimento: "",
        tipo_documento_id: "",
        cliente_id: "",
        fornecedor_id: "",
        centro_custo_id: "",
        status: "",
        valor_total: "",
        descricao: "",
      
      }),
    };
  },
  mounted() {
    this.form.exercicio_id = this.sessions_exercicio ? this.sessions_exercicio.id : "";
    this.form.periodo_id = this.periodo_sessao ? this.periodo_sessao.id : "";

  },
  methods: {
    getPeriodos({ id, text }) {
      axios
        .get(`/get-periodos/${this.form.exercicio_id}`)
        .then((response) => {
          // this.form.numero = response.data.diario.numero + ".";
          this.lista_periodos = [];
          this.lista_periodos = response.data.periodos;
        })
        .catch((error) => {});
    },

    submit() {
      this.$Progress.start();

      this.form.post(route("facturas.store"), {
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
            title: "Impossível salvar o movimento!",
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
    