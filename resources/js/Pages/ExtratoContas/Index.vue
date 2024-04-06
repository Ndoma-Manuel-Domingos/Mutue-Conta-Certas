<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">EXTRACTO DE CONTA</h1>
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
                
                  <!-- <div class="col-12 col-md-3 mb-4">
                    <label for="conta_id" class="form-label">Contas</label>
                    <Select2
                      id="conta_id"
                      v-model="conta_id"
                      :options="contas"
                      :settings="{ width: '100%' }"
                    />
                  </div> -->
                
                  <div class="col-12 col-md-3 mb-4">
                    <label for="subconta_id" class="form-label">Contas</label>
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
            <!-- <div class="card-footer">
              <a href="/extratos-contas" class="d-block btn btn-primary text-uppercase"><i class="fas fa-broom"></i> Limpar a Pesquisa</a>
            </div> -->
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
              <a
                @click="imprimirExtratoExcel()"
                class="btn btn-sm mx-1 btn-success float-right">
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
                    <td>MOEDA - <strong>AOA</strong></td>
                    <th></th>
                    <th class="text-right">Saldo Geral</th>
                    <th class="text-info">{{ resultado.total_debito > resultado.total_credito ? formatValor(resultado.total_debito - resultado.total_credito) : '-' }}</th>
                    <th class="text-danger">{{ resultado.total_credito > resultado.total_debito ? formatValor(resultado.total_credito - resultado.total_debito) : '-'}}</th>
                  </tr>
                  
                  <tr>
                    <th></th>
                    <th></th>
                    <th class="text-right">Total</th>
                    <th class="text-info">{{ formatarValorMonetario(resultado.total_debito)  }}</th>
                    <th class="text-danger">{{ formatarValorMonetario(resultado.total_credito) }}</th>
                  </tr>
                  
                  
                  <tr>
                    <th style="width: 70px">Nº de Movimento</th>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Débito</th>
                    <th>Crédito</th>
                  </tr>                 
                 
                </thead>

                <tbody>

                  <tr v-for="item in movimentos" :key="item">
                    <td style="text-align: center">{{ item.movimento.lancamento_atual }}</td>
                    <td>{{ item.movimento.data_lancamento }}</td>
                    <td>{{ item.descricao }}</td>
                    <!-- <td>{{ item.subconta.designacao }}</td> -->
                    <td class="text-info"><strong>{{ item.debito == 0 ? '-' : formatarValorMonetario(item.debito) }}</strong></td>
                    <td class="text-danger"><strong>{{ item.credito == 0 ? '-' : formatarValorMonetario(item.credito) }}</strong></td>
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
    "requests"
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
    
      conta_id: this.requests.conta_id ?? "3",
      subconta_id: this.requests.subconta_id ?? "",
      // exercicio_id: "",
      // periodo_id: "",
      data_inicio: this.requests.data_inicio ?? new Date().toISOString().substr(0, 10),
      data_final: this.requests.data_final ?? new Date().toISOString().substr(0, 10),

      params: {},
    };
  },
  mounted() {
    console.log(this.requests);
    
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

    // exercicio_id: function (val) {
    //   this.params.exercicio_id = val;
    //   this.updateData();
    // },

    // periodo_id: function (val) {
    //   this.params.periodo_id = val;
    //   this.updateData();
    // },

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
      window.open("imprimir-extrato?conta_id="+this.conta_id+"&subconta_id="+this.subconta_id+"&data_inicio="+this.data_inicio+"&data_final="+this.data_final+"");
    },
    imprimirExtratoExcel(){
      window.open("imprimir-extrato-excel?conta_id="+this.conta_id+"&subconta_id="+this.subconta_id+"&data_inicio="+this.data_inicio+"&data_final="+this.data_final+"");
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
    
    formatarValorMonetario(valor) {
        // Converter o número para uma string e separar parte inteira da parte decimal
        let partes = String(valor).split('.');
        let parteInteira = partes[0];
        let parteDecimal = partes.length > 1 ? '.' + partes[1] : '';
    
        // Adicionar separadores de milhar
        parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
        // Retornar o valor formatado
        return parteInteira + parteDecimal;
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
  
  