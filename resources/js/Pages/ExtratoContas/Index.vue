<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM DE EXTRATO DE CONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/dashboard">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Listagem</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-body">
              <form action="">
                <div class="row">
                
                  <div class="col-12 col-md-3 mb-4">
                    <label for="conta_id" class="form-label">Contas</label>
                    <Select2
                      id="conta_id"
                      v-model="conta_id"
                      :options="contas"
                      :settings="{ width: '100%' }"
                    />
                  </div>
                
                  <div class="col-12 col-md-3 mb-4">
                    <label for="subconta_id" class="form-label">SubContas</label>
                    <Select2
                      id="subconta_id"
                      v-model="subconta_id"
                      :options="subcontas"
                      :settings="{ width: '100%' }"
                    />
                  </div>

                  <!-- <div class="col-12 col-md-2 mb-4">
                    <label for="exercicio_id" class="form-label"
                      >Exercícios</label
                    >
                    <Select2
                      id="exercicio_id"
                      v-model="exercicio_id"
                      :options="exercicios"
                      :settings="{ width: '100%' }"
                    />
                  </div> -->

                  <!-- <div class="col-12 col-md-2 mb-4">
                    <label for="periodo_id" class="form-label">Períodos</label>
                    <Select2
                      id="periodo_id"
                      v-model="periodo_id"
                      :options="periodos"
                      :settings="{ width: '100%' }"
                    />
                  </div> -->

                  <div class="col-12 col-md-3 mb-4">
                    <label for="data_inicio" class="form-label"
                      >Data Inicio</label
                    >
                    <input
                      type="date"
                      id="data_inicio"
                      v-model="data_inicio"
                      class="form-control"
                      placeholder="Ex: 1.1, 1.1.1"
                    />
                  </div>

                  <div class="col-12 col-md-3 mb-4">
                    <label for="data_final" class="form-label"
                      >Final Inicio</label
                    >
                    <input
                      type="date"
                      id="data_final"
                      v-model="data_final"
                      class="form-control"
                      placeholder="Ex: 1.1, 1.1.1"
                    />
                  </div>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <a href="/extratos-contas" class="d-block btn btn-primary text-uppercase"><i class="fas fa-broom"></i> Limpar a Pesquisa</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <a
                @click="imprimirExtrato()"
                class="btn btn-sm mx-1 btn-danger float-right"
              >
                <i class="fas fa-file-pdf"></i> Visualizar</a
              >
              <a href="" class="btn btn-sm mx-1 btn-success float-right">
                <i class="fas fa-file-excel"></i> Exportar</a
              >
            </div>
            <div class="card-body">
              <table
                class="table table-bordered table-hover"
                id="tabela_de_balancetes"
              >
                <thead>
                  <tr>
                    <th>Conta</th>
                    <th>Descrição</th>
                    <th>Débito</th>
                    <th>Crédito</th>
                    <th>Data</th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                    <th></th>
                    <th></th>
                    <th>{{ formatValor(resultado.total_debito) }}</th>
                    <th>{{ formatValor(resultado.total_credito) }}</th>
                    <th></th>
                  </tr>

                  <tr v-for="item in movimentos" :key="item">
                    <td>{{ item.subconta.numero }}</td>
                    <td>{{ item.subconta.designacao }}</td>
                    <td>{{ formatValor(item.debito) }}</td>
                    <td>{{ formatValor(item.credito) }}</td>
                    <td>{{ item.movimento.data_lancamento }}</td>
                  </tr>
                </tbody>
              </table>
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
    "exercicios",
    "periodos",
    "subcontas",
    "movimentos",
    "resultado",
    "contas",
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
    
      conta_id: "",
      subconta_id: "",
      exercicio_id: "",
      periodo_id: "",
      data_inicio: "",
      data_final: "",

      params: {},
    };
  },
  mounted() {

    $("#tabela_de_balancetes").DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: true,
    });
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
    
    conta_id: function (val) {
      this.params.conta_id = val;
      this.updateData();
    },

    subconta_id: function (val) {
      this.params.subconta_id = val;
      this.updateData();
    },

    exercicio_id: function (val) {
      this.params.exercicio_id = val;
      this.updateData();
    },

    periodo_id: function (val) {
      this.params.periodo_id = val;
      this.updateData();
    },

    data_inicio: function (val) {
      this.params.data_inicio = val;
      this.updateData();
    },

    data_final: function (val) {
      this.params.data_final = val;
      this.updateData();
    },
  },

  methods: {
    imprimirExtrato(){
      window.open("imprimir-extrato");
    },
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/extratos-contas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },
  },
};
</script>
  
  