<template>
  <MainLayoutsAdmin>
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
                          <th style="cursor: pointer;">Nº</th>
                          <th style="cursor: pointer;">Diário</th>
                          <!-- <th style="cursor: pointer;">Documento</th> -->
                          <th>Data</th>
                          <th>Exercício</th>
                          <th>Operador</th>
                          <th class="text-right">Débito</th>
                          <th class="text-right">Crédito</th>
                          <!-- <th class="text-right">Ações</th> -->
                        </tr>
                      </thead>
                      
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td> 
                          <td></td>
                          <td class="text-right"><strong>Total Debito: 100.000,00</strong></td>
                          <td class="text-right"><strong>Total Credito: 100.000,00</strong></td>
                          <td></td>
                        </tr>
                      </tbody>
                      
                      <tfoot>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td> 
                          <td></td>
                          <td class="text-right"><strong>Total Debito: 100.000,00</strong></td>
                          <td class="text-right"><strong>Total Credito: 100.000,00</strong></td>
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
  </MainLayoutsAdmin>
</template>

<script>
import MainLayoutsAdmin from './Layouts/MainLayoutsAdmin.vue'
export default {
  props: ["movimentos"],
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
	components: { MainLayoutsAdmin },
  data() {
    return {};
  },
  mounted() {
    console.log(this.movimentos)
  },
  methods: {
        
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
    
  },
};
</script>

