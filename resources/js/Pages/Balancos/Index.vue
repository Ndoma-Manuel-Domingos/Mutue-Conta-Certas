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

                    <div class="col-12 col-md-4 mb-4">
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
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <a class="btn btn-sm mx-1 btn-danger float-right">
                  <i class="fas fa-file-pdf"></i> Visualizar</a
                >
                <a href="" class="btn btn-sm mx-1 btn-success float-right">
                  <i class="fas fa-file-excel"></i> Exportar</a
                >
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover table-bordered text-nowrap">
                    <thead>
                      <tr>
                        <th>Designação</th>
                        <th style="width: 5px" class="text-center">Notas</th>
                        <th>2022</th>
                        <th>2021</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- <template> -->
                      <!-- ================================================================================ -->
                      <tr>
                        <th colspan="4">Activos não correntes:</th>
                      </tr>
                      <tr v-for="item in conta_do_activos_nao_corrente" :key="item">
                        <td>
                          {{ item.conta.numero ?? "" }} -
                          {{ item.conta.designacao ?? "" }}
                        </td>
                        <td class="text-center">4</td>
                        <td>
                          {{
                            item.debito > item.credito
                              ? item.debito - item.credito
                              : item.credito - item.debito
                          }}
                        </td>
                        <td>0</td>
                      </tr>
                      <tr>
                        <td><small><strong>Outros activos não correntes ..................................................</strong></small></td>
                        <td></td>
                        <td></td>
                        <td><small><strong>0</strong></small></td>
                      </tr>
                      <tr>
                        <th></th>
                        <th>0</th>
                        <td></td>
                        <th>0</th>
                      </tr>
                      <!-- </template> -->
                      <!-- ================================================================================ -->
                      <!-- <template> -->
                      <tr>
                        <th colspan="4">Activos correntes:</th>
                      </tr>

                      <tr v-for="item in conta_do_activos_corrente" :key="item">
                        <td>
                          {{ item.conta.numero ?? "" }} -
                          {{ item.conta.designacao ?? "" }}
                        </td>
                        <td>0</td>
                        <td>
                          {{
                            item.debito > item.credito
                              ? item.debito - item.credito
                              : item.credito - item.debito
                          }}
                        </td>
                        <td class="text-center">0</td>
                      </tr>
                      
                      <tr>
                        <td><small><strong>Outros activos correntes ..................................................</strong></small></td>
                        <td>4</td>
                        <td><small><strong>0</strong></small></td>
                        <td><small><strong>0</strong></small></td>
                      </tr>

                      <tr>
                        <th></th>
                        <th></th>
                        <th>0</th>
                        <th>0</th>
                      </tr>

                      <tr>
                        <th>Total do activo</th>
                        <th></th>
                        <th>0</th>
                        <th>0</th>
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
                        <td></td>
                        <td>0,00</td>
                        <td>0,00</td>
                      </tr>

                      <tr>
                        <!-- ---------------------------------------- -->
                        <td>Reservas ....................................</td>
                        <td></td>
                        <td>0,00</td>
                        <td>0,00</td>
                      </tr>

                      <tr>
                        <th></th>
                        <th></th>
                        <th>0</th>
                        <th>0</th>
                      </tr>

                      <!-- ================================================================================ -->

                      <tr>
                        <th colspan="4">Passivo não corrente:</th>
                      </tr>
                      <tr v-for="item in conta_do_passivos_nao_corrente" :key="item">
                        <td>
                          {{ item.conta.numero ?? "" }} -
                          {{ item.conta.designacao ?? "" }}
                        </td>
                        <td class="text-center">0</td>
                        <td>
                          {{
                            item.debito > item.credito
                              ? item.debito - item.credito
                              : item.credito - item.debito
                          }}
                        </td>
                        <td>0</td>
                      </tr>
                      
                      <tr>
                        <td><small><strong>Outros passivo não correntes ..................................................</strong></small></td>
                        <td><small><strong></strong></small></td>
                        <td><small><strong>0</strong></small></td>
                        <td><small><strong>0</strong></small></td>
                      </tr>

                      <tr>
                        <th></th>
                        <th></th>
                        <th>0</th>
                        <th>0</th>
                      </tr>

                      <!-- ================================================================================ -->

                      <tr>
                        <th colspan="4">Passivo corrente:</th>
                      </tr>
                      <tr v-for="item in conta_do_passivos_corrente" :key="item">
                        <td>
                          {{ item.conta.numero ?? "" }} -
                          {{ item.conta.designacao ?? "" }}
                        </td>
                        <td class="text-center">0</td>
                        <td>
                          {{
                            item.debito > item.credito
                              ? item.debito - item.credito
                              : item.credito - item.debito
                          }}
                        </td>
                        <td></td>
                        <td>0</td>
                      </tr>
     
                      <tr>
                        <td><small><strong>Outros passivo correntes ..................................................</strong></small></td>
                        <td><small><strong></strong></small></td>
                        <td><small><strong>0</strong></small></td>
                        <td><small><strong>0</strong></small></td>
                      </tr>

                      <tr>
                        <th></th>
                        <th></th>
                        <th>0</th>
                        <th>0</th>
                      </tr>
                 
                      <tr>
                        <th></th>
                        <th>Total do capital próprio e passivo</th>
                        <th>0</th>
                        <th>0</th>
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
  props: ["exercicios", "periodos",  "conta_do_activos_corrente", "conta_do_activos_nao_corrente" ,"conta_do_passivos_corrente", "conta_do_passivos_nao_corrente"],
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
      // Converter o número para uma string e separar parte inteira da parte decimal
      let partes = String(valor).split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes.length > 1 ? "." + partes[1] : "";

      // Adicionar separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

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
  
  