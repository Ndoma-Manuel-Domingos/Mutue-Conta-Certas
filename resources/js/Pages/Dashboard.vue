<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">DASHBOARD</h1>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-12 col-md-3">
            <div class="small-box bg-info">
              <div class="inner">
                <h4>{{ formatarValorMonetario(resultado.debito ?? 0) }}</h4>
                <p>Débito Geral Fluxo do Caixa</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <Link href="" class="small-box-footer"
                >Mais detalhe <i class="fas fa-arrow-circle-right"></i
              ></Link>
            </div>
          </div>

          <div class="col-lg-3 col-12 col-md-3">
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>{{ formatarValorMonetario(resultado.credito ?? 0) }}</h4>
                <p>Crédito Geral Fluxo do Caixa</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <Link href="" class="small-box-footer"
                >Mais detalhe <i class="fas fa-arrow-circle-right"></i
              ></Link>
            </div>
          </div>

          <div class="col-lg-3 col-12 col-md-3">
            <div class="small-box bg-success">
              <div class="inner">
                <h4 v-if="(resultado.debito ?? 0) > (resultado.credito ?? 0)">
                  {{
                    formatarValorMonetario((resultado.debito ?? 0) - (resultado.credito ?? 0))
                  }}
                </h4>
                <h4 v-else-if="(resultado.debito ?? 0) < (resultado.credito ?? 0)">
                  {{
                    formatarValorMonetario((resultado.credito ?? 0) - (resultado.debito ?? 0))
                  }}
                </h4>
                <h4 v-else>---</h4>
                <p>Saldo Geral Fluxo do Caixa</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <Link href="" class="small-box-footer"
                >Mais detalhe<i class="fas fa-arrow-circle-right"></i
              ></Link>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <MovimentosChart />
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-12">
            <div>
              <div class="card">
                <div class="card-header">
                  <h5>Listagem dos últimos Movimentos</h5>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th style="cursor: pointer">Nº</th>
                        <th style="cursor: pointer">Diário</th>
                        <th>Data</th>
                        <th>Exercício</th>
                        <th>Operador</th>
                        <th>Descrição</th>
                        <th class="text-right">Débito</th>
                        <th class="text-right">Crédito</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in movimentos" :key="item">
                        <td>{{ item.id }}</td>
                        <td>
                          {{ item.diario.numero }} -
                          {{ item.diario.designacao }}
                        </td>
                        <td>{{ item.data_lancamento }}</td>
                        <td>{{ item.exercicio.designacao }}</td>
                        <td>{{ item.criador ? item.criador.name: "" }}</td>
                        <td>
                          {{ item.tipo_documento.numero }} -
                          {{ item.tipo_documento.designacao }}
                        </td>
                        <td class="text-info text-right">
                          <strong>{{
                            item.debito == 0
                              ? "-"
                              : formatarValorMonetario(item.debito ?? 0)
                          }}</strong>
                        </td>
                        <td class="text-danger text-right">
                          <strong>{{
                            item.credito == 0
                              ? "-"
                              : formatarValorMonetario(item.credito ?? 0)
                          }}</strong>
                        </td>
                      </tr>
                    </tbody>

                    <tfoot></tfoot>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
  </MainLayouts>
</template>

<script>
import MovimentosChart from "../components/MovimentosChart.vue";

export default {
  props: ["movimentos", "resultado"],
  components: {
    MovimentosChart,
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
    return {};
  },
  mounted() {
    console.log(this.movimentos);
  },
  methods: {
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
  },
};
</script>

