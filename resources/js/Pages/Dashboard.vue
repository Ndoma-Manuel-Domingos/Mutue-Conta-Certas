<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">DASHBOARD</h1>
          </div>
          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
              <div>
                <div class="card">
                  <div class="card-header">
                    <h5>Listagem dos ultimos Movimentos</h5>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Codigo</th>
                          <th class="text-right">Debito</th>
                          <th class="text-right">Crédito</th>
                          <th class="text-right">Data</th>
                          <th class="text-right">Operador</th>
                        </tr>
                      </thead>
                      
                      <tbody>
                        <tr v-for="i in (6)" :key="i">
                          <td>##</td>
                          <td class="text-right">10.000,00</td>
                          <td class="text-right">9.000,00</td>
                          <td class="text-right">12-12-2023</td>
                          <td class="text-right">Teste</td>
                        </tr>
                        
                      </tbody>
                      
                      <tfoot>
                        <tr>
                          <td></td>
                          <td class="text-right"><strong>Total Debito: 100.000,00</strong></td>
                          <td class="text-right"><strong>Total Credito: 100.000,00</strong></td>
                          <td></td>
                          <td></td>
                        </tr>
                        
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-right"><strong>Total Saldo: 100.000,00</strong></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="card-footer">
                  
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
export default {
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {},
};
</script>

