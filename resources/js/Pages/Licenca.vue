<template>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="content-wrapper kanban">
      <section class="content-header">
        <div class="container-fluid">
          <!-- <div class="row">
            <div class="col-sm-6">
              <h1>Kanban Board</h1>
            </div>
            <div class="col-sm-6 d-none d-sm-block">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Kanban Board</li>
              </ol>
            </div>
          </div> -->
        </div>
      </section>
      <section class="content">
        <div class="container-fluid h-100">
          <div v-for="item in licencas" :key="item.id">
            <div class="card card-row" :class="item.card">
              <div class="card-header">
                <h3 class="card-title">{{ item.titulo }}</h3>
              </div>
              <div class="card-body">
                <div class="card card-info card-outline" >
                  <div class="card-header">
                    <h5 class="card-title">Módulos</h5>
                  </div>
                  <div class="card-body" style="height: 150px;">

                    <div class="custom-control custom-checkbox" v-for="modulos in item.modulos" :key="modulos">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox2"
                        disabled
                      />
                      <label for="customCheckbox2" class="custom-control-label"
                        >{{modulos.modulo.designacao}}</label
                      >
                    </div>

                  </div>
                </div>
                <div class="card card-light card-outline">
                  <div class="card-header">
                    <h5 class="card-title">Descrição</h5>
                  </div>
                  <div class="card-body">
                    <p>
                      {{ item.designacao }}
                    </p>
                  </div>

                  <div class="card-footer">
                    <p>
                      {{ formatarValorMonetario(item.preco) }}KZ
                    </p>
                  </div>
                </div>
                <div class="card card-light card-outline">
                  <div class="card-body">
                    <a :href="`assinar-contrato-licencas/${ item.id }`" class="btn btn-lg d-block" :class="item.btn"
                      >Assinar</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </body>
</template>

<script>
import AuthLayouts from "./Layouts/AuthLayouts.vue";
export default {
  layout: AuthLayouts,
  props: ["licencas"],
  methods:{
    
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
  }
};
</script>

