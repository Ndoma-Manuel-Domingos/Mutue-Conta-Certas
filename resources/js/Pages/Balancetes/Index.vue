<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">VISUALIZAR BALANCETE</h1>
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
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="">
                  <div class="row">
                    <div class="col-12 col-md-4 mb-4">
                      <label for="tipo_balancete_id" class="form-label"
                        >Tipo de Balancete</label
                      >
                      <Select2
                        id="tipo_balancete_id"
                        v-model="tipo_balancete_id"
                        :options="tipos_balancetes"
                        :settings="{ width: '100%' }"
                      />
                    </div>

                    <!-- <div class="col-12 col-md-4 mb-4">
                        <label for="subconta_id" class="form-label">Contas</label>
                        <Select2  id="subconta_id" v-model="subconta_id"
                          :options="contas" :settings="{ width: '100%' }"
                        />
                    </div> -->

                    <div class="col-12 col-md-2 mb-4">
                      <label for="exercicio_id" class="form-label"
                        >Exercícios</label
                      >
                      <Select2
                        id="exercicio_id"
                        v-model="exercicio_id"
                        :options="exercicios"
                        :settings="{ width: '100%' }"
                      />
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="periodo_id" class="form-label"
                        >Períodos</label
                      >
                      <Select2
                        id="periodo_id"
                        v-model="periodo_id"
                        :options="periodos"
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
                        :min="minDate(userYear)" :max="maxDate(userYear)"
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
                        :min="minDate(userYear)" :max="maxDate(userYear)"
                      />
                    </div>
                  </div>
                </form>
              </div>
              <!-- <div class="card-footer">
                <a href="/balancetes" class="d-block btn btn-primary text-uppercase"><i class="fas fa-broom"></i> Limpar a Pesquisa</a>
              </div> -->
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <a
                  @click="imprimirBalancete()"
                  class="btn btn-sm mx-1 btn-danger float-right"
                >
                  <i class="fas fa-file-pdf"></i> Visualizar</a
                >
                <a href="" class="btn btn-sm mx-1 btn-success float-right" @click="ExportarExcelBalanco()">
                  <i class="fas fa-file-excel"></i> Exportar Balanço</a
                >
              </div>
              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                >
                  <thead>
                    <tr>
                      <th rowspan="2">Nº</th>
                      <th rowspan="2">Conta</th>
                      <th rowspan="2">Descrição</th>
                      <th colspan="2" class="text-center" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">Dados do Período</th>
                      <th colspan="2" class="text-center">Movimentos</th>
                      <th colspan="2" class="text-center">Saldo</th>
                    </tr>

                    <tr>
                      <th class="text-left" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">Débito</th>
                      <th class="text-left" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">Crébito</th>
                      <th class="text-left">Débito</th>
                      <th class="text-left">Crédito</th>
                      <th class="text-left">Devedor</th>
                      <th class="text-left">Credor</th>
                    </tr>

                    <tr>
                      <th></th>
                      <th></th>
                      <th class="text-right">Total</th>
                      <th class="text-primary text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ resultado_por_conta.total_debito == 0 ? '-' : formatarValorMonetario(resultado_por_conta.total_debito) }}</th>
                      <th class="text-danger text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ resultado_por_conta.total_credito == 0 ? '-' : formatarValorMonetario(resultado_por_conta.total_credito) }}</th>
                      <th class="text-primary text-right">{{ resultado_por_conta.total_debito == 0 ? '-' : formatarValorMonetario(resultado_por_conta.total_debito) }}</th>
                      <th class="text-danger text-right">{{ resultado_por_conta.total_credito == 0 ? '-' : formatarValorMonetario(resultado_por_conta.total_credito) }}</th>
                      <th class="text-primary text-right">{{ resultado_por_conta.total_debito > resultado_por_conta.total_credito ? formatarValorMonetario(resultado_por_conta.total_debito - resultado_por_conta.total_credito) : '-' }}</th>
                      <th class="text-danger text-right">{{ resultado_por_conta.total_credito > resultado_por_conta.total_debito ? formatarValorMonetario(resultado_por_conta.total_credito - resultado_por_conta.total_debito) : '-'  }}</th>


                      <!-- <th class="text-primary text-right">{{ resultado.total_movimento_debito == 0 ? '-' : formatarValorMonetario(resultado.total_movimento_debito) }}</th>
                      <th class="text-danger text-right">{{ resultado.total_movimento_credito == 0 ? '-' : formatarValorMonetario(resultado.total_movimento_credito) }}</th> -->
                      <!-- <th class="text-primary text-right">{{ resultado.total_movimento_debito > resultado.total_movimento_credito ? formatarValorMonetario(resultado.total_movimento_debito - resultado.total_movimento_credito) : '-' }}</th>
                      <th class="text-danger text-right">{{ resultado.total_movimento_credito > resultado.total_movimento_debito ? formatarValorMonetario(resultado.total_movimento_credito - resultado.total_movimento_debito) : '-'  }}</th> -->

                    </tr>

                    <template v-for="(item, index) in registros.data" :key="index">
                      <!-- CLASSES -->
                      <tr v-show="tipo_balancete_id == 3 || tipo_balancete_id == 4">
                        <th colspan="3" class="text-uppercase">CLASSE {{ item.classe.numero ?? '' }} - {{ item.classe.designacao ?? '' }}</th>
                        <th class="text-primary text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item.conta.items_movimentos[0] ? (item.conta.items_movimentos[0].TotalDebito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito)) : '-' }}</th>
                        <th class="text-danger text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item.conta.items_movimentos[0] ? (item.conta.items_movimentos[0].TotalCredito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito)) : '-' }}</th>
                        <th class="text-primary text-right">-</th>
                        <th class="text-danger text-right">-</th>
                        <th class="text-primary text-right">-</th>
                        <th class="text-danger text-right">-</th>
                      </tr>
                      <!-- CONTAS -->
                      <tr>
                        <th>Nº</th>
                        <th class="text-uppercase" colspan="2">{{ item.conta.numero ?? '' }} - {{ item.conta.designacao ?? '' }}</th>
                        <th class="text-primary text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item.conta.items_movimentos[0] ? (item.conta.items_movimentos[0].TotalDebito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito)) : '-' }}</th>
                        <th class="text-danger text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item.conta.items_movimentos[0] ? (item.conta.items_movimentos[0].TotalCredito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito)) : '-' }}</th>
                        <th class="text-primary text-right">{{ item.conta.items_movimentos[0].TotalDebito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito) }}</th>
                        <th class="text-danger text-right">{{ item.conta.items_movimentos[0].TotalCredito == 0 ? '-' : formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito) }}</th>
                        <th class="text-primary text-right">{{ item.conta.items_movimentos[0].TotalDebito == 0 ? '-' : (item.conta.items_movimentos[0].TotalDebito > item.conta.items_movimentos[0].TotalCredito ? formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito - item.conta.items_movimentos[0].TotalCredito) : '-')  }}</th>
                        <th class="text-danger text-right">{{ item.conta.items_movimentos[0].TotalCredito == 0 ? '-' : (item.conta.items_movimentos[0].TotalCredito > item.conta.items_movimentos[0].TotalDebito ? formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito - item.conta.items_movimentos[0].TotalDebito) : '-')  }}</th>
                      </tr>
                      <!-- ITENS -->
                      <template  v-for="(item2, index) in item.sub_contas_empresa" :key="index">
                        <tr v-show="tipo_balancete_id == 3 || tipo_balancete_id == 4">
                          <td>{{ index + 1 }}</td>
                          <td>{{ item2.numero ?? '' }}</td>
                          <td><a :href="`extratos-contas?subconta_id=${item2.id}`" class="text-primary text-uppercase">{{ item2.designacao ?? '' }}</a></td>
                          <td class="text-primary  text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item2.items_movimentos[0] ? (item2.items_movimentos[0].total_debito == 0 ? '-' : formatarValorMonetario(item2.items_movimentos[0].total_debito) ) : '-' }}</td>
                          <td class="text-danger  text-right" v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{ item2.items_movimentos[0] ?  (item2.items_movimentos[0].total_credito == 0 ? '-' : formatarValorMonetario(item2.items_movimentos[0].total_credito))  : '-' }}</td>
                          <td class="text-primary  text-right">{{ item2.items_movimentos[0] ? (item2.items_movimentos[0].total_debito == 0 ? '-' : formatarValorMonetario(item2.items_movimentos[0].total_debito) ) : '-' }}</td>
                          <td class="text-danger  text-right">{{ item2.items_movimentos[0] ?  (item2.items_movimentos[0].total_credito == 0 ? '-' : formatarValorMonetario(item2.items_movimentos[0].total_credito))  : '-' }}</td>
                          <td class="text-primary  text-right">{{ item2.items_movimentos[0] ? (item2.items_movimentos[0].total_debito > item2.items_movimentos[0].total_credito ? formatarValorMonetario(item2.items_movimentos[0].total_debito - item2.items_movimentos[0].total_credito) : '-' )  : '-' }}</td>
                          <td class="text-danger  text-right">{{ item2.items_movimentos[0] ?  (item2.items_movimentos[0].total_credito > item2.items_movimentos[0].total_debito ? formatarValorMonetario(item2.items_movimentos[0].total_credito - item2.items_movimentos[0].total_debito) :'-' ) : '-' }}</td>
                        </tr>
                      </template>

                    </template>
                  </thead>
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
    "movimentos",
    "resultado",
    "resultado_por_conta",
    "exercicios",
    "periodos",
    "contas",
    "tipos_balancetes",
    "registros",
    "requests"
  ],
  components: {
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
    periodo_sessao() {
      return this.$page.props.sessions.periodo_sessao;
    },
  },
  data() {
    return {
      operador_id: "",
      tipo_diario: "",
      tipo_documento: "",

      tipo_balancete_id: this.requests.tipo_balancete_id ?? "3",

      exercicio_id: "",
      periodo_id: "",

      userYear: "",

      data_inicio: "", // new Date().toISOString().substr(0, 10),
      data_final:  "", // new Date().toISOString().substr(0, 10),

      subconta_id: "",

      params: {},
    };
  },
  mounted() {
    this.exercicio_id = this.sessions_exercicio ? this.sessions_exercicio.id : "";
    this.periodo_id = this.periodo_sessao ? this.periodo_sessao.id : "";
    this.tipo_balancete_id = "3";

    const year = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";

    this.data_inicio = `${year}-01-01`;
    this.data_final = `${year}-12-31`;

    this.userYear = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";

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

    exercicio_id: function (val) {
      this.params.exercicio_id = val;
      this.updateData();
    },

    tipo_balancete_id: function (val) {
      this.params.tipo_balancete_id = val;
      this.updateData();
    },

    subconta_id: function (val) {
      this.params.subconta_id = val;
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
      this.$inertia.get("/balancetes", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    minDate(year) {
      return `${year}-01-01`; // Primeiro dia do ano especificado
    },

    maxDate(year) {
      return `${year}-12-31`; // Último dia do ano especificado
    },

    imprimirBalancete() {
      window.open(
        `imprimir-balancete?tipo_balancete_id=${this.tipo_balancete_id}&exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`
      );
    },
    ExportarExcelBalanco() {
      window.open(
        `exportar-balancete-excel?tipo_balancete_id=${this.tipo_balancete_id}&exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`
      );
    },

    formatarValorMonetario(valor) {
      // Converter o número para uma string e separar parte inteira da parte decimal
      let partes = String(valor).split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes.length > 1 ? "." + partes[1] : "";

      // Adicionar separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

      // Retornar o valor formatado
      return parteInteira + parteDecimal;
    },

    obterMesAtual() {
      // Criar um objeto de data
      let dataAtual = new Date();

      // Obter o mês (vale de 0 a 11, onde 0 representa janeiro e 11 representa dezembro)
      let mes = dataAtual.getMonth();

      // Adicionar 1 ao mês, pois os meses são indexados a partir de 0
      // para que janeiro seja 1, fevereiro seja 2 e assim por diante
      return mes + 1;
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

