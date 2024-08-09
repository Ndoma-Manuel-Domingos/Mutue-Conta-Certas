<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">EMISSÃO DE BALANÇO</h1>
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



                    <!-- <div class="col-12 col-md-3 mb-4">
                      <label for="periodo_id" class="form-label"
                        >Períodos</label
                      >
                      <
                        id="periodo_id"
                        v-model="periodo_id"
                        :options="periodos"
                        :settings="{ width: '100%' }"
                      />
                    </div> -->

                    <!-- <div class="col-12 col-md-4 mb-4">
                      <label for="data_inicio" class="form-label"
                        >Data Inicio</label
                      >
                      <input
                        type="date"
                        id="data_inicio"
                        v-model="data_inicio"
                        class="form-control"
                        :min="minDate(userYear)" :max="maxDate(userYear)"
                      />
                    </div>

                    <div class="col-12 col-md-4 mb-4">
                      <label for="data_final" class="form-label"
                        >Final Inicio</label
                      >
                      <input
                        type="date"
                        id="data_final"
                        v-model="data_final"
                        :min="minDate(userYear)" :max="maxDate(userYear)"
                        class="form-control"
                      />
                    </div> -->

                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <a class="btn btn-sm mx-1 btn-danger float-right" @click="imprimirBalancete()">
                  <i class="fas fa-file-pdf"></i> Visualizar</a
                >
                <a href="" class="btn btn-sm mx-1 btn-success float-right" @click="ExportarExcelBalanco()">
                  <i class="fas fa-file-excel"></i> Exportar Balanço</a
                >
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                      <tr>
                        <th>Designação</th>
                        <th style="width: 5px" class="text-center">Notas</th>
                        <th class="text-center">{{ exercicio_actual[0].text}}</th>
                        <th class="text-center">{{ exercicio_anterior.designacao }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <template> -->
                      <!-- ================================================================================ -->
                      <tr>
                        <th colspan="4">Activos não correntes:</th>
                      </tr>
                      <!-- <tr v-for="(item, index) in conta_do_activos_nao_corrente" :key="index">
                        <td>{{ item.conta.numero ?? "" }} - {{ item.conta.designacao ?? "" }}</td>
                        <td class="text-center"><a href="">{{ item.conta.nota ?? "" }}</a></td>
                        <td>{{ item.debito > item.credito ? item.debito - item.credito : item.credito - item.debito }}</td>
                        <td>0</td>
                      </tr> -->
                      <tr>

                        <td>Imobilizações corpóreas ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=4">4</a></td>
                        <!-- <td class="text-right" style="color: red;" v-if="total_imobilizacoes_corporeas < 0">{{ total_imobilizacoes_corporeas * -1}}</td> -->
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(total_imobilizacoes_corporeas) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Imobilizações incorpóreas ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=5">5</a></td>
                        <!-- <td class="text-right" style="color: red;" v-if="total_imobilizacoes_incorporeas < 0">{{ total_imobilizacoes_incorporeas * -1}}</td> -->
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(total_imobilizacoes_incorporeas) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Investimentos em subsidiárias e associadas ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=6">6</a></td>
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(total_subssidiarias) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Outros activos financeiros ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=7">7</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>
                      <tr>
                        <td>Outros activos não correntes ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=0">9</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <th>Total de Activos não Correntes</th>
                        <th></th>
                        <th class="text-right">{{ formatarValorMonetario(total_subssidiarias+total_imobilizacoes_incorporeas+total_imobilizacoes_corporeas) }}</th>
                        <th class="text-right">-</th>
                      </tr>
                      <!-- </template> -->
                      <!-- ================================================================================ -->
                      <!-- <template> -->
                      <tr>
                        <th colspan="4">Activos correntes:</th>
                      </tr>

                      <!-- <tr v-for="(item, index) in conta_do_activos_corrente" :key="index">
                        <td>{{ item.conta.numero ?? "" }} - {{ item.conta.designacao ?? "" }}</td>
                        <td class="text-center"><a href="">{{ item.conta.nota ?? "" }}</a></td>
                        <td>{{ item.debito > item.credito ? item.debito - item.credito : item.credito - item.debito }}</td>
                        <td>0</td>
                      </tr> -->

                      <tr>
                        <td>Existências ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=8">8</a></td>
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(total_existencias) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Contas a receber ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=9">9</a></td>
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(contas_receber) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Disponibilidades ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=10">10</a></td>
                        <td class="text-right" style="color: blue;">{{ formatarValorMonetario(total_disponibilidade) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Outros activos correntes  ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=11">11</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <th>Total do Activo Corrente</th>
                        <th class="text-center"></th>
                        <th class="text-right">{{ formatarValorMonetario(total_disponibilidade + contas_receber + total_existencias) == 0 ?  formatarValorMonetario(total_disponibilidade + contas_receber + total_existencias) : formatarValorMonetario(total_subssidiarias+total_imobilizacoes_incorporeas+total_imobilizacoes_corporeas)}}</th>
                        <th class="text-right">-</th>
                      </tr>
                      <!-- </template> -->

                      <!-- <template> -->
                      <!-- ================================================================================ -->
                      <tr>
                        <th colspan="4">CAPITAL PRÓPRIO E PASSIVO</th>
                      </tr>

                      <tr>
                        <th colspan="4">Capital próprio:</th>
                      </tr>
                      <tr>
                        <td>Capital ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=12">12</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <!-- ---------------------------------------- -->
                        <td>Reservas ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=13">13</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <!-- ---------------------------------------- -->
                        <td>Resultados transitados ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=14">14</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>
                      <tr>
                        <!-- ---------------------------------------- -->
                        <td>Resultados do exercício ....................................</td>
                        <td class="text-center"><a href="get-subcontas?nota=13">13</a></td>
                        <td class="text-right">{{ formatarValorMonetario(resultado_liquido_do_exercicio) }}</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <th></th>
                        <th class="text-center"></th>
                        <th class="text-right"></th>
                        <th class="text-right">-</th>
                      </tr>

                      <!-- ================================================================================ -->

                      <tr>
                        <th colspan="4">Passivo não corrente:</th>
                      </tr>

                      <tr>
                        <td>Impostos diferidos. . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=16">16</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Provisões para pensões. . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=17">17</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Provisões para outros riscos e encargos. . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=18">18</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Outros passivos não correntes. . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=19">19</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>
                      <!-- <tr v-for="(item, index) in conta_do_passivos_nao_corrente" :key="index">
                        <td>{{ item.conta.numero ?? "" }} - {{ item.conta.designacao ?? "" }}</td>
                        <td class="text-center"><a href="">{{ item.conta.nota ?? "" }}</a></td>
                        <td>{{ item.debito > item.credito ? item.debito - item.credito : item.credito - item.debito }}</td>
                        <td>0</td>
                      </tr> -->

                      <tr>
                        <th></th>
                        <th class="text-center"></th>
                        <th class="text-right">-</th>
                        <th class="text-right">-</th>
                      </tr>

                      <!-- ================================================================================ -->

                      <tr>
                        <th colspan="4">Passivo corrente:</th>
                      </tr>

                      <tr>
                        <td>Contas a pagar. . . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=19">19</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Empréstimos de curto prazo. . . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=20">20</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>


                      <tr>
                        <td>Parte cor. dos empr. a médio e longo prazos. . . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=15">15</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <tr>
                        <td>Outros passivos correntes. . . . .</td>
                        <td class="text-center"><a href="get-subcontas?nota=21">21</a></td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                      </tr>

                      <!-- <tr v-for="(item, index) in conta_do_passivos_corrente" :key="index">
                        <td>{{ item.conta.numero ?? "" }} - {{ item.conta.designacao ?? "" }}</td>
                        <td class="text-center"><a href="">{{ item.conta.nota ?? "" }}</a></td>
                        <td>{{ item.debito > item.credito ? item.debito - item.credito : item.credito - item.debito }}</td>
                        <td></td>
                      </tr> -->

                      <tr>
                        <th></th>
                        <th class="text-center"></th>
                        <th class="text-right">-</th>
                        <th class="text-right">-</th>
                      </tr>

                      <tr>
                        <th></th>
                        <th class="text-center">Total do capital próprio e passivo</th>
                        <th class="text-right">-</th>
                        <th class="text-right">-</th>
                      </tr>
                      <!-- </template> -->
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <!-- <Link href="" class="text-secondary">
                  Total Registro: {{ movimentos.total }}</Link
                >
                <Paginacao
                  :links="movimentos.links"
                  :prev="movimentos.prev_page_url"
                  :next="movimentos.next_page_url"
                /> -->
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
  props: ["exercicios", "periodos", "contas_receber", "total_imobilizacoes_corporeas" ,
   "total_imobilizacoes_incorporeas", "conta_do_activos_nao_corrente",
   "subcontas_movimentos" ,"conta_do_passivos_corrente","total_existencias" ,
   "conta_do_passivos_nao_corrente", "total_disponibilidade", "total_subssidiarias",
   "exercicio_anterior", "exercicio_actual", "mensagem", "imposto_sobre_lucro", "resultado_liquido_do_exercicio"
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

  },
  data() {
    return {
      exercicio_id: "",
      periodo_id: "",

      userYear: "",

      data_inicio: "", // new Date().toISOString().substr(0, 10),
      data_final: "",  //new Date().toISOString().substr(0, 10),

      params: {},
    };
  },
  mounted() {

    const year = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";

    this.data_inicio = `${year}-01-01`;
    this.data_final = `${year}-12-31`;

    this.exercicio_id = this.sessions_exercicio
      ? this.sessions_exercicio.id
      : "";

    this.userYear = this.sessions_exercicio ? this.sessions_exercicio.designacao : "";

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
      this.$inertia.get("/balancos", this.params, {
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

    imprimirBalancete() {
      window.open(
        `imprimir-balanco?exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`
      );
    },

    ExportarExcelBalanco() {
      window.open(
        `exportar-excel?exercicio_id=${this.exercicio_id}&periodo_id=${this.periodo_id}&data_inicio=${this.data_inicio}&data_final=${this.data_final}`
      );
    },

    buscarSubcontas(nota){
      this.$Progress.start();
      this.$inertia.get(`get-subcontas?nota=${nota}`);
    },

  },
};
</script>

