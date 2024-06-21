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
      
      </div>
    </div>
    
  </MainLayoutsAdmin>
</template>

<script>
  import MainLayoutsAdmin from './Layouts/MainLayoutsAdmin.vue'
  export default {
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
    },
    methods: {
    },
  };
</script>

