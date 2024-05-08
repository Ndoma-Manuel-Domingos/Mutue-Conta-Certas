<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">FLUXO DE CAIXA</h1>
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
                      <label for="subconta_id" class="form-label"
                        >Contas</label
                      >
                      <Select2
                        id="subconta_id"
                        v-model="subconta_id"
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

                    <div class="col-12 col-md-2 mb-4">
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
              <!-- <div class="card-footer">
              <a href="/extratos-contas" class="d-block btn btn-primary text-uppercase"><i class="fas fa-broom"></i> Limpar a Pesquisa</a>
            </div> -->
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <a
                    href="/fluxos-caixas/create"
                    class="btn btn-sm btn-info mx-1"
                  >
                    <i class="fas fa-plus"></i> FLUXO DE CAIXA</a
                  >
                  
                  <a
                    href="/demonstracao-fluxo-caixa"
                    class="btn btn-sm btn-info mx-1"
                  >
                    <i class="fas fa-plus"></i> DEMOSTRAÇÃO DE FLUXO DE CAIXA</a
                  >
                  
                  <a class="btn btn-sm float-right btn-danger mx-1" @click="imprimirPDF()">
                    <i class="fas fa-file-pdf"></i> Imprimir
                  </a>
                </h3>
              </div>
              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                  id="tabela_de_diarias"
                >
                  <thead>
                    <tr>
                      <th>Nº</th>
                      <th>Diário</th>
                      <th class="text-right">Débito</th>
                      <th class="text-right">Crédito</th>
                      <th class="text-right">Saldo</th>
                      <th class="text-right" rowspan="2">Exercício</th>
                      <th class="text-right" rowspan="2">Período</th>
                      <th class="text-right" rowspan="2">Data</th>
                      <th class="text-right" rowspan="2" style="width: 300px">Ações</th>
                    </tr>
                    
                    <tr>
                      <th class="text-right" colspan="2">TOTAL</th>
                      <th class="text-right text-primary">{{ formatarValorMonetario(resultado.debito ?? 0) }}</th>
                      <th class="text-right text-danger">{{ formatarValorMonetario(resultado.credito ?? 0) }}</th>
                      <th class="text-right text-primary" v-if="resultado.debito > resultado.credito">{{ formatarValorMonetario(resultado.debito - resultado.credito) }}</th>
                      <th class="text-right text-danger" v-if="resultado.credito > resultado.debito">{{ formatarValorMonetario(resultado.credito - resultado.debito) }}</th>
                    </tr>
                    
                  </thead>

                  <tbody>
                    <tr v-for="item in movimentos" :key="item">
                      <td>{{ item.lancamento_atual ?? "" }}</td>
                      <td>{{ item.diario.designacao ?? "" }}</td>
                      <td class="text-right text-primary">{{ item.debito == 0 ? "-" : formatarValorMonetario(item.debito) }}</td>
                      <td class="text-right text-danger">{{ item.credito == 0 ? "-" : formatarValorMonetario(item.credito) }}</td>
                      
                      <td class="text-right text-primary" v-if="item.debito > item.credito">{{ formatarValorMonetario(item.debito - item.credito) }}</td>
                      <td class="text-right text-primary" v-if="item.debito == item.credito">{{ '-' }}</td>
                      <td class="text-right text-danger" v-if="item.credito > item.debito">{{  formatarValorMonetario(item.credito - item.debito) }}</td>
                      <td class="text-right">
                        {{ item.exercicio.designacao ?? "" }}
                      </td>
                      <td class="text-right">
                        {{ item.periodo.designacao ?? "" }}
                      </td>
                      <td class="text-right">
                        {{ item.data_lancamento ?? '' }}
                      </td>
                      <td>
                        <div class="float-right">
                          <a
                            :href="`/fluxos-caixas/${item.id}`"
                            class="btn btn-sm btn-info mx-2"
                            ><i class="fas fa-info"></i> Detalhe</a
                          >
                          <a
                            :href="`/fluxos-caixas/${item.id}/edit`"
                            class="btn btn-sm btn-success mr-2"
                            ><i class="fas fa-edit"></i> Editar</a
                          >
                          
                          <a v-if="item.credito != 0"
                            :href="`/fluxos-caixas-imprimir-nota-entregue?id=${item.id}`"
                            class="btn btn-sm btn-danger mx-2" target="_blink"
                            ><i class="fas fa-file-pdf"></i> Imprimir</a
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
import Paginacao from "../../components/Paginacao.vue";

export default {
  props: ["movimentos", "resultado", "exercicios", "periodos", "periodo", "subcontas"],
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
      subconta_id: "",
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
    const month = this.periodo.numero;
        
    this.data_inicio = `${year}-05-01`;
    this.data_final = `${year}-05-30`;
    
    // this.data_inicio = `${year}-${month}-01`;
    // this.data_final = `${year}-${month}-30`;
    
    this.userYear = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";
    this.userMonth = this.periodo.numero;
       
  
    $("#tabela_de_diarias").DataTable({
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
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/fluxos-caixas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    minDate(year) {
      return `${year}-05-01`; // Primeiro dia do ano especificado
    },
    
    maxDate(year) {
      return `${year}-05-30`; // Último dia do ano especificado
    },
    // minDate(year) {
    //   return `${year}-01-01`; // Primeiro dia do ano especificado
    // },
    
    // maxDate(year) {
    //   return `${year}-12-31`; // Último dia do ano especificado
    // },

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
      window.open(`imprimir-fluxo-caixa?exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`);
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