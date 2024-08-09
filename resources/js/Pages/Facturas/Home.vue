<template>
    <MainLayouts>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-info">CONTROLE DE FACTURAS</h1>
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
                  <h4>{{ formatarValorMonetario(facturas_pendentes ?? 0) }}</h4>
                  <p>Facturas Pendente</p>
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
                  <h4>{{ formatarValorMonetario(facturas_pagas ?? 0) }}</h4>
                  <p>Facturas Pagas</p>
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
                  <h4>{{ formatarValorMonetario(facturas_em_atraso ?? 0) }}</h4>
                  <p>Facturas Em Atraso</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <Link href="" class="small-box-footer"
                  >Mais detalhe <i class="fas fa-arrow-circle-right"></i
                ></Link>
              </div>
            </div>

          </div>
        </div>
      </div>
    </MainLayouts>
  </template>
  
  <script>
  
  export default {
    props: [
        "facturas_em_atraso",
        "facturas_pendentes",
        "facturas_pagas",
    ],
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

      };
    },
    mounted() {
    },
    methods: {
    
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
    },
  };
  </script>
  
  