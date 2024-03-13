<template>
  <body class="container login-page" style="background-color: #fff">
    <div class="row text-center">
      <div class="col-12 col-md-12 my-5">
        <h4>Qual empresa Pretendes usar?</h4>

        <p>
          Lorem ipsum dolor sit amet consectetur, adipisicing elit. Saepe,
          perferendis exercitationem, quo mollitia veritatis quae quibusdam
          nihil consequuntur aut autem nam tempore doloremque unde cupiditate
          laudantium dicta repellendus omnis consequatur?
        </p>
      </div>
    </div>
    <!-- Three columns of text below the carousel -->
    <div class="row text-center">
      <div
        class="col-12 col-md-6 col-lg-6"
        v-for="item in empresas.empresas"
        :key="item"
      >
        <!-- <div class="card">	 -->
          <div class="card-body">
            <h4 class="fw-normal mt-3">{{ item.nome_empresa }}</h4>
            <p>{{ item.codigo_empresa }}</p>
          </div>
          <!-- <div class="card-footer"> -->
            <p>
              <a class="btn btn-secondary" @click="iniciar_sessao_empresa(item)">Iniciar Sessão &raquo;</a>
            </p>
          <!-- </div> -->
        <!-- </div> -->
      </div>
    </div>
  </body>
</template>

<script>
import AuthLayouts from "./Layouts/AuthLayouts.vue";
export default {
  layout: AuthLayouts,

  props: ["empresas"],
  
  methods: {
    iniciar_sessao_empresa(item) {
      this.$Progress.start();

      axios.get(`/empresas/iniciar-sessão/${item.id}`)
        .then((response) => {
          this.$Progress.finish();
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Sessão Inicializado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
      
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
            timer: 4000
          })
          
        });
    },
  },
};
</script>

