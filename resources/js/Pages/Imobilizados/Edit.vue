<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">EDITAR IMOBILIZADOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/imobilizados">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Editar Imobilizado</li>
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
                    <div class="col-12 col-md-2 mb-4">
                      <label for="exercicio_id" class="form-label"
                        >Exercícios</label
                      >
                      <Select2
                        v-model="form.exercicio_id"
                        id="exercicio_id"
                        class="col-12 col-md-12"
                        :options="exercicios"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.exercicio_id"
                        >{{ form.errors.exercicio_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="periodo_id" class="form-label">Período</label>
                      <Select2
                        v-model="form.periodo_id"
                        id="periodo_id"
                        class="col-12 col-md-12"
                        :options="periodos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.periodo_id"
                        >{{ form.errors.periodo_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="conta" class="form-label">Conta</label>
                      <input
                        type="text"
                        id="conta"
                        v-model="form.conta"
                        class="form-control"
                        placeholder="Conta Ex:11.11"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.conta"
                        >{{ form.errors.conta }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
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

                    <div class="col-12 col-md-3 mb-4">
                      <label for="classificacao_id" class="form-label"
                        >Classificação</label
                      >
                      <Select2
                        v-model="form.classificacao_id"
                        id="classificacao_id"
                        class="col-12 col-md-12"
                        :options="categorias"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.classificacao_id"
                        >{{ form.errors.classificacao_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="origem_id" class="form-label">Origem</label>
                      <Select2
                        v-model="form.origem_id"
                        id="origem_id"
                        class="col-12 col-md-12"
                        :options="origens"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.origem_id"
                        >{{ form.errors.origem_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
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

                    <div class="col-12 col-md-2 mb-4">
                      <label for="valor_aquisicao" class="form-label"
                        >Valor de Aquisição</label
                      >
                      <input
                        type="text"
                        id="valor_aquisicao"
                        v-model="form.valor_aquisicao"
                        class="form-control"
                        placeholder="Valor de Aquisição"
                        @keypress="validateInput"
                        @input="formatInput"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.valor_aquisicao"
                        >{{ form.errors.valor_aquisicao }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="quantidade" class="form-label"
                        >Quantidade</label
                      >
                      <input
                        type="text"
                        id="quantidade"
                        v-model="form.quantidade"
                        class="form-control"
                        placeholder="Quantidades"
                        @keypress="validateInput"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.quantidade"
                        >{{ form.errors.quantidade }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_aquisicao" class="form-label"
                        >Data de Aquisição</label
                      >
                      <input
                        type="date"
                        id="data_aquisicao"
                        v-model="form.data_aquisicao"
                        class="form-control"
                        placeholder="Data de Aquisição"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.data_aquisicao"
                        >{{ form.errors.data_aquisicao }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_utilizacao" class="form-label"
                        >Data de Utilização</label
                      >
                      <input
                        type="date"
                        id="data_utilizacao"
                        v-model="form.data_utilizacao"
                        class="form-control"
                        placeholder="Data de Aquisição"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.data_utilizacao"
                        >{{ form.errors.data_utilizacao }}</span
                      >
                    </div>

                    <div class="col-12 col-md-12 mb-4">
                      <label for="" class="form-label"
                        >Decreto Presidencial n.º 207/15 - Regime de
                        Reintegrações e Amortizações Aplicável aos Bens do
                        Activo Imobilizado das Sociedades e Entidades Sujeitas
                        ao Imposto Industrial</label
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="amortizacao_id" class="form-label"
                        >Categorias</label
                      >
                      <Select2
                        v-model="amortizacao_id"
                        id="amortizacao_id"
                        class="col-12 col-md-12"
                        :options="amortizacoes"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.amortizacao_id"
                        >{{ form.errors.amortizacao_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="amortizacao_item_id" class="form-label"
                        >Amortizações</label
                      >
                      <Select2
                        v-model="form.amortizacao_item_id"
                        id="amortizacao_item_id"
                        class="col-12 col-md-12"
                        :options="amortizacoesItem"
                        :settings="{ width: '100%' }"
                        @select="getDadosItem($event)"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.amortizacao_item_id"
                        >{{ form.errors.amortizacao_item_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="taxa" class="form-label"
                        >Taxa% de Amortização</label
                      >
                      <input
                        type="text"
                        id="taxa"
                        v-model="form.taxa"
                        disabled
                        class="form-control"
                        placeholder="Taxa% de Amortização"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.taxa"
                        >{{ form.errors.taxa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="vida_util" class="form-label"
                        >Vida Útil Anos</label
                      >
                      <input
                        type="text"
                        id="vida_util"
                        v-model="form.vida_util"
                        disabled
                        class="form-control"
                        placeholder="Vida Útil Anos"
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
      </div>
    </div>
  </MainLayouts>
</template>
    
<script>
export default {
  props: [
    "categorias",
    "amortizacoes",
    "amortizacoesItem",
    "items",
    "exercicios",
    "periodos",
    "subcontas",
    "imobilizado",
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
      estados: [
        { id: "activo", text: "Activo" },
        { id: "desactivo", text: "Desactivo" },
      ],

      params: {},

      origens: [
        { id: "aquisicao", text: "Aquisição" },
        { id: "construcao", text: "Construção" },
        { id: "fabrico", text: "Fabrico" },
        { id: "doacao", text: "Doação" },
        { id: "reparacao", text: "Reparação" },
        { id: "benfeitoria", text: "Benfeitoria" },
      ],

      form: {
        exercicio_id: this.imobilizado.exercicio_id ?? "",
        periodo_id: this.imobilizado.periodo_id ?? "",
        amortizacao_id: this.imobilizado.amortizacao_id ?? "",
        classificacao_id: this.imobilizado.classificacao_id ?? "",
        amortizacao_item_id: this.imobilizado.amortizacao_item_id ?? "",
        valor_aquisicao: this.imobilizado.valor_aquisicao ?? "",
        quantidade: this.imobilizado.quantidade ?? "",
        designacao: this.imobilizado.designacao ?? "",
        conta: this.imobilizado.conta ?? "",
        origem_id: this.imobilizado.origem_id ?? "",
        data_aquisicao: this.imobilizado.data_aquisicao ?? "",
        data_utilizacao: this.imobilizado.data_utilizacao ?? "",
        estado: this.imobilizado.status,
        itemId: this.imobilizado.id ?? "",
      },
    };
  },
  watch: {
    options: function (val) {
      this.params.page = val.page;
      this.params.page_size = val.itemsPerPage;
      if (val.sortBy.length != 0) {
        this.params.sort_by = val.sortBy[0];
        this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
      } else {
        this.params.sort_by = null;
        this.params.order_by = null;
      }
      this.updateData();
    },

    amortizacao_id: function (val) {
      this.params.amortizacao_id = val;
      this.updateData();
    },

    amortizacao_item_id: function (val) {
      this.params.amortizacao_item_id = val;
      this.updateData();
    },
  },
  mounted() {},
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/imobilizados/create", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    submit() {
      this.$Progress.start();
      axios
        .put(`/imobilizados/${this.form.itemId}`, this.form)
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
    