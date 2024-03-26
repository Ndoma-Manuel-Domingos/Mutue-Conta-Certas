<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM DE BALANÇO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
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
                    
                    <div class="col-12 col-md-3 mb-4">
                        <label for="exercicio_id" class="form-label">Exercícios</label>
                        <Select2  id="exercicio_id" v-model="exercicio_id"
                          :options="exercicios" :settings="{ width: '100%' }" 
                        />
                    </div>
                    
                    <div class="col-12 col-md-3 mb-4">
                      <label for="periodo_id" class="form-label">Períodos</label>
                        <Select2 id="periodo_id" v-model="periodo_id"
                          :options="periodos" :settings="{ width: '100%' }" 
                        />
                    </div>
                  
                    <div class="col-12 col-md-3 mb-4">
                      <label for="data_inicio" class="form-label">Data Inicio</label>
                      <input type="date" id="data_inicio" v-model="data_inicio" class="form-control" placeholder="Ex: 1.1, 1.1.1">
                    </div>
                    
                    <div class="col-12 col-md-3 mb-4">
                      <label for="data_final" class="form-label">Final Inicio</label>
                      <input type="date" id="data_final" v-model="data_final" class="form-control" placeholder="Ex: 1.1, 1.1.1">
                    </div>
                   
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header"> 
                <a class="btn btn-sm mx-1 btn-danger float-right"> <i class="fas fa-file-pdf"></i> Visualizar</a>
                <a href="" class="btn btn-sm mx-1 btn-success float-right"> <i class="fas fa-file-excel"></i> Exportar</a>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Exemplo</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                
                      <tr>
                        <td>Exemplo</td>
                      </tr>
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

  props: [
    "exercicios",
    "periodos",
  ],
  components:{
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
      data_inicio: "",
      data_final: "",
      params: {},
    };
  },
  mounted() {},
    
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
  
  