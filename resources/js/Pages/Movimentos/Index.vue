<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">LISTAGEM DE MOVIMENTOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Listagem</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-12 col-md-12">
            <form action="">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    
                    <div class="col-12 col-md-3 mb-4">
                      <label for="sub_contas_id" class="form-label"
                        >Subcontas</label
                      >
                      <Select2
                        id="sub_contas_id"
                        v-model="sub_contas_id"
                        :options="subcontas"
                        :settings="{ width: '100%' }"
                      />
                    </div>
                    
                    <div class="col-12 col-md-3 mb-4">
                      <label for="exercicio_id" class="form-label"
                        >Exercícios</label
                      >
                      <Select2
                        id="exercicio_id"
                        v-model="exercicio_id"
                        :options="exercicios"
                        :settings="{ width: '100%' }"
                        @select="getPeriodos($event)"
                      />
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="periodo_id" class="form-label"
                        >Períodos</label
                      >
                      <Select2
                        id="periodo_id"
                        v-model="periodo_id"
                        :options="lista_periodos"
                        :settings="{ width: '100%' }"
                      />
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_inicio" class="form-label"
                        >Data Inicial</label
                      >
                      <input
                        type="date"
                        id="data_inicio"
                        v-model="data_inicio"
                        class="form-control"
                      />
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_final" class="form-label"
                        >Data Final</label
                      >
                      <input
                        type="date"
                        id="data_final"
                        v-model="data_final"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
            </form>
          </div>
        </div>
      
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <a href="/movimentos/create" class="btn btn-info btn-sm"> <i class="fas fa-plus"></i> LANÇAR MOVIMENTOS</a>
                <a href="/fluxos-caixas/create" class="btn btn-info btn-sm mx-1"> <i class="fas fa-plus"></i> FLUXO DE CAIXA</a>
                <a @click="imprimirPlano()" class="btn btn-sm mx-1 btn-danger float-right"> <i class="fas fa-file-pdf"></i> Visualizar</a>
                <a href="" class="btn btn-sm mx-1 btn-success float-right" @click="ExportarExcelMovimento()">
                  <i class="fas fa-file-excel"></i> Exportar Balanço</a
                >
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="tabela_de_movimentos">
                  <thead>
                    <tr>
                      <th style="cursor: pointer;">Nº</th>
                      <th>Data</th>
                      <th>Exercício</th>
                      <th>Período</th>
                      <th style="cursor: pointer;">descrição</th>
                      <th style="cursor: pointer;">Origem</th>
                      <th class="text-right">Débito</th>
                      <th class="text-right">Crédito</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>

                  <tbody>

                    <tr v-for="item in movimentos" :key="item">
                      <td>{{ item.id }}</td>
                      <td>{{ item.data_lancamento }}</td>
                      <td>{{ item.exercicio.designacao }}</td>
                      <td>{{ item.periodo.designacao }}</td>
                      <td>{{ item.descricao }}</td>
                      <td>{{ item.tipo_instituicao == "propina" ? "Contas Certas" : "Mutue Negócios" }}</td>
                      <td class="text-info text-right"><strong>{{ item.debito == 0 ? '-' : formatarValorMonetario(item.debito) }}</strong></td>
                      <td class="text-danger text-right"><strong>{{ item.credito == 0 ? '-' : formatarValorMonetario(item.credito) }}</strong></td>
                      <td>
                        <div class="float-right">
                          <a :href="`/movimentos/${item.id}/edit`" class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i> Editar</a>
                          <a :href="`/movimentos/${item.id}`" class="btn btn-sm btn-info mx-1"><i class="fas fa-info-circle"></i> Detalhe</a>
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

import Paginacao from "../../components/Paginacao.vue";

export default {

  props: [
    'movimentos',
    "exercicios",
    "periodos",
    "subcontas",
  ],
  components:{
    Paginacao,
  },
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
      sub_contas_id: "",
      exercicio_id: "",
      periodo_id: "",
      data_inicio: "", // new Date().toISOString().substr(0, 10),
      data_final:  "", // new Date().toISOString().substr(0, 10),
      
      lista_periodos: [],

      params: {},
    };
  },
  mounted() {
    $('#tabela_de_movimentos').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
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
    
    sub_contas_id: function (val) {
      this.params.sub_contas_id = val;
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
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/movimentos", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    getPeriodos({ id, text }) {
      axios
        .get(`/get-periodos/${this.exercicio_id}`)
        .then((response) => {
          // this.form.numero = response.data.diario.numero + ".";
          this.lista_periodos = [];
          this.lista_periodos = response.data.periodos;
          
        })
        .catch((error) => {});
    },
    
    imprimirPlano() {
      window.open("imprimir-movimentos");
    },

        
    formatarValorMonetario(valor) {
      // Converte o valor para uma string com duas casas decimais
      let valorFormatado = Number(valor).toFixed(2);
  
      // Separa a parte inteira da parte decimal
      let partes = valorFormatado.split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes[1] ? "," + partes[1] : "";
  
      // Adiciona separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  
      // Retorna o valor formatado
      return parteInteira + parteDecimal;
    },

    ExportarExcelMovimento() {
      window.open(
        `exportar-movimento-excel`
      );
    },
    
    formatValor(atual) {
      // Converte o valor para um número com duas casas decimais
      const valor = Number(atual).toFixed(2);
    
      // Formata o valor para a moeda especificada (AOA)
      const valorFormatado = Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "AOA",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }).format(valor);
    
      return valorFormatado;
    },
  },
};
</script>

