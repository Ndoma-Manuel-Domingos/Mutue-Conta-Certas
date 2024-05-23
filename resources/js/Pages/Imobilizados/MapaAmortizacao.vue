<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">MAPA DE REINTEGRAÇÕES E AMORTIZAÇÕES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/imobilizados">Dashboard</a></li>
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
              <div class="card-header">
                <h3 class="card-title">
                  
                  <button class="btn btn-sm float-right btn-danger mx-1" @click="imprimirBalancete()">
                    <i class="fas fa-file-pdf"></i> Imprimir
                  </button>
                </h3>

              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                  <thead>
                    <tr>
                      <th class="text-left" rowspan="3">Nº</th>
                      <th class="text-left" rowspan="3">Conta</th>
                      <th class="text-left" rowspan="3">Descrição</th>
                      
                      <th class="text-center" colspan="2">Data</th>
                      
                      <th class="text-left" rowspan="3">Isenção no Código Aduaneiro (Activo Importado) (Sim ou Não)</th>
                      <th class="text-left" rowspan="3">Origem</th>
                      <th class="text-left" rowspan="3">Número de anos de utilidade esperada</th>
                      <th class="text-left" rowspan="3">Ano da reavaliação</th>
                      <th class="text-right" rowspan="3">Valor de Aquisição</th>
                      <!-- <th class="text-left" rowspan="3">Valor do acréscimo da reavaliação</th> -->
                      <th class="text-left" rowspan="3">Quantidade</th>
                      
                      <th class="text-center" colspan="6">Reintegrações e amortizações</th>
                      
                    </tr>
                    
                    <tr>
                                          
                      <th rowspan="2">Aquisição</th>
                      <th rowspan="2">Início de utilização</th>
                                       
                      <th rowspan="2">Valores de exercícios anteriores</th>
                      <th class="text-center" colspan="3">Do exercício</th>
                      <th rowspan="2">Amortizações acumuladas</th>
                      <th rowspan="2">Valor contabilístico</th>
                      
                    </tr>
                                      
                    <tr>
                            
                      <th>Taxa%</th>
                      <th>Taxa corrigida %</th>
                      <th>Valores</th>
                      
                    </tr>
                
                  </thead>

                  <tbody>
                    
                    <tr v-for="(item, index) in imobilizados" :key="index">
                        <td class="text-left">{{ index + 1 }}</td>
                        <td class="text-left">{{ item.conta ?? "" }}</td>
                        <td class="text-left">{{ item.designacao ?? "" }}</td>
                        <td class="text-left">{{ item.data_aquisicao ?? "" }}</td>
                        <td class="text-left">{{ item.data_utilizacao ?? "" }}</td>
                        <td class="text-left">{{ item.sigla ?? "" }}</td>
                        <td class="text-left">{{ item.origem_id ?? "" }}</td>
                        <td class="text-left">{{ item.amortizacao_item.vida_util }}</td>
                        <td class="text-left">-----</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                        <td class="text-left">{{ formatarValorMonetario(item.quantidade) }}</td>
                        <td class="text-left">{{ item.designacao }}</td>
                        <td class="text-left">{{ item.amortizacao_item.taxa }}</td>
                        <td class="text-left">{{ item.designacao }}</td>
                        <td class="text-right">{{ formatarValorMonetario(calcularAmortizacaoAnual(item.valor_aquisicao, item.amortizacao_item.taxa, item.amortizacao_item.vida_util))   }}</td>
                        <td class="text-left">{{ item.designacao }}</td>
                        <td class="text-left">{{ item.designacao }}</td>
                    </tr>
                    
                    <!-- <tr v-for="(item, index) in imobilizados" :key="index">
                        <td class="text-left">{{ item.designacao }}</td>
                        <td class="text-center">{{ item.data_aquisicao }}</td>
                        <td class="text-center">{{ item.quantidade }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                        <td class="text-center">{{ item.amortizacao_item.vida_util }}</td>
                        <td class="text-right">-</td>
                        <td>{{ item.amortizacao_item.taxa }}%</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao)  }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                    </tr> -->
                  
                    
                  </tbody>
                  
                  <tfoot>
                    <!-- <tr>
                        <td class="text-center" colspan="3">Total Geral (ainda estamos a estudar)</td>
                        
                        <td class="text-right">462 000,00</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">462 000,00</td>
                        <td class="text-right">462 000,00</td>
                        <td class="text-right">462 000,00</td>
                    </tr> -->
                  </tfoot>
                </table>
              
                <!-- <table class="table table-bordered table-hover" id="tabela_de_diarias">
                  <thead>
                    <tr>
                      <th class="text-left" rowspan="3">Descrição</th>
                      <th class="text-center" rowspan="3">Ano</th>
                      <th class="text-center" rowspan="3">Qtd</th>
                      <th class="text-center">Valores de Aquisição</th>
                      <th class="text-center">Anos em Esperas</th>
                      <th class="text-center" colspan="4">Reintegrações e amortizações</th>
                      <th class="text-center" rowspan="3">Activo Imobilizado <br> (Valores líquidos)</th>
                    </tr>
                    
                    <tr>
                      <th class="text-center" rowspan="2">(c)</th>
                      <th class="text-center" rowspan="2">(d)</th>
                      
                      <th class="text-center" rowspan="2">De exercícios <br> anteriores</th>
                      <th class="text-center" colspan="2">Do exercícios</th>
                      <th class="text-center" rowspan="2">Acumuladas</th>
                    </tr>
                    
                    <tr>
                      <th class="text-center">Taxa</th>
                      <th class="text-center">Valores</th>
                    </tr>
                    
                  </thead>

                  <tbody>
                    
                    <tr v-for="(item, index) in imobilizados" :key="index">
                        <td class="text-left">{{ item.designacao }}</td>
                        <td class="text-center">{{ item.data_aquisicao }}</td>
                        <td class="text-center">{{ item.quantidade }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                        <td class="text-center">{{ item.amortizacao_item.vida_util }}</td>
                        <td class="text-right">-</td>
                        <td>{{ item.amortizacao_item.taxa }}%</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao)  }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                        <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                    </tr>
                  
                    
                  </tbody>
                  
                  <tfoot>
                    <tr>
                        <td class="text-center" colspan="3">Total Geral (ainda estamos a estudar)</td>
                        
                        <td class="text-right">462 000,00</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">462 000,00</td>
                        <td class="text-right">462 000,00</td>
                        <td class="text-right">462 000,00</td>
                    </tr>
                  </tfoot>
                </table> -->
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
  props: ["imobilizados"],
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
        params: {},
    };
  },
  mounted() {
    // $('#tabela_de_diarias').DataTable({
    //   "responsive": true, "lengthChange": true, "autoWidth": true,
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

  },
  methods: {
    calcularAmortizacaoAnual(valorAquisicao, taxaAnual, periodoVidaUtil) {
        // Garante que os valores de entrada são inteiros
        valorAquisicao = Math.round(valorAquisicao);
        periodoVidaUtil = Math.round(periodoVidaUtil);
        taxaAnual = Math.round(taxaAnual);
        
        // Calcula a amortização anual
        const amortizacaoAnual = (valorAquisicao * taxaAnual) / periodoVidaUtil;
        
        // Arredonda o valor da amortização para o inteiro mais próximo
        return Math.round(amortizacaoAnual);
    },
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/mapa-amortizacoes", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
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
    
    mudar_estado(item) {
      this.$Progress.start();

      axios
        .get(`/diarios/${item.id}`)
        .then((response) => {
          this.$Progress.finish();
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Estado Alterado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          window.location.reload();
        })
        .catch((error) => {
          this.$Progress.fail();
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Correu um erro ao Estado Alterado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });
        });
    },
        
    imprimirBalancete() {
      window.open(
        `imprimir-mapa-amortizacoes?exercicio_id=${this.exercicio_id}`
      );
    },

    
  },
};
</script>