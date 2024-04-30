<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">DEMONSTRAÇÃO DE FLUXO DE CAIXA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/fluxos-caixas">Dashboard</a>
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
                    <div class="col-12 col-md-3 mb-4">
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

                    <div class="col-12 col-md-3 mb-4">
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
                        :min="minDate(userYear)" :max="maxDate(userYear)"
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
                        :min="minDate(userYear)" :max="maxDate(userYear)"
                      />
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fluxo de caixa das actividades operacionais</h3>
                  <a class="btn btn-sm float-right btn-danger mx-1" @click="imprimirPDF()">
                    <i class="fas fa-file-pdf"></i> Imprimir
                  </a>
              </div>
              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                  id="tabela_de_diarias2"
                >
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th class="text-right">Valores</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=clientes" class="text-info">Dinheiro recebido de Clientes</a></td>
                        <td class="text-right">{{ formatarValorMonetario(dinheiro_recebido_clientes) }}</td>
                    </tr>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=mercadorias" class="text-info">Dinheiro pagos em mercadorias</a></td>
                        <td class="text-right">{{ formatarValorMonetario(dinheiro_recebido_fornecedores) }}</td>
                    </tr>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=operacionais" class="text-info">Dinheiro pagos em salários e custos operacionais</a></td>
                        <td class="text-right">{{ formatarValorMonetario(dinheiro_custo) }}</td>
                    </tr>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=jursos" class="text-info">Dinheiro pagos em juros</a></td>
                        <td class="text-right">{{ formatarValorMonetario(dinheiro_pagos_juros) }}</td>
                    </tr>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=imposto" class="text-info">Dinheiro pagos em impostos</a></td>
                        <td class="text-right">{{ formatarValorMonetario(dinheiro_imposto) }}</td>
                    </tr>
                    <tr>
                        <td><a href="/demonstracao-fluxo-caixa-detalhe?demonstracao=outros" class="text-info">Outros</a></td>
                        <td class="text-right">{{ formatarValorMonetario(outros) }}</td>
                    </tr>
                  </tbody>
                  
                  <tfoot>
                    <tr>
                        <th>Caixa liquida gerada nas actividades operacionais</th>
                        <th class="text-right">{{ formatarValorMonetario(dinheiro_recebido_clientes + dinheiro_recebido_fornecedores + dinheiro_custo + dinheiro_pagos_juros + dinheiro_imposto + outros) }}</th>
                    </tr>
                  </tfoot>
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
    "exercicios",
    "periodos",
    "dinheiro_recebido_clientes", 
    "dinheiro_recebido_fornecedores", 
    "dinheiro_custo", 
    "dinheiro_imposto", 
    "dinheiro_pagos_juros", 
    "outros"
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
      exercicio_id: "",
      periodo_id: "",
      data_inicio: "",
      data_final: "",

      params: {},
    };
  },
  mounted() {
    
    this.exercicio_id = this.sessions_exercicio ? this.sessions_exercicio.id : "";
    this.periodo_id = this.periodo_sessao ? this.periodo_sessao.id : "";
    
    const year = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";
    
    this.data_inicio = `${year}-04-01`;
    this.data_final = `${year}-04-30`;
        
    this.userYear = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";
  
    // $("#tabela_de_diarias").DataTable({
    //   responsive: true,
    //   lengthChange: true,
    //   autoWidth: true,
    // });
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
      this.$inertia.get("/demonstracao-fluxo-caixa", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    minDate(year) {
      return `${year}-04-01`; // Primeiro dia do ano especificado
    },
    
    maxDate(year) {
      return `${year}-04-30`; // Último dia do ano especificado
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

    imprimirPDF(){
      window.open(`imprimir-controlo-fluxo-caixa?exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`);
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