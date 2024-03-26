<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM DE BALANCETES</h1>
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
                  
                    <div class="col-12 col-md-4 mb-4">
                        <label for="conta_id" class="form-label">Contas</label>
                        <Select2  id="conta_id" v-model="conta_id"
                          :options="contas" :settings="{ width: '100%' }" 
                        />
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                        <label for="exercicio_id" class="form-label">Exercícios</label>
                        <Select2  id="exercicio_id" v-model="exercicio_id"
                          :options="exercicios" :settings="{ width: '100%' }" 
                        />
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                      <label for="periodo_id" class="form-label">Períodos</label>
                        <Select2 id="periodo_id" v-model="periodo_id"
                          :options="periodos" :settings="{ width: '100%' }" 
                        />
                    </div>
                  
                    <div class="col-12 col-md-2 mb-4">
                      <label for="data_inicio" class="form-label">Data Inicio</label>
                      <input type="date" id="data_inicio" v-model="data_inicio" class="form-control" placeholder="Ex: 1.1, 1.1.1">
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
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
                <a @click="imprimirPlano()" class="btn btn-sm mx-1 btn-danger float-right"> <i class="fas fa-file-pdf"></i> Visualizar</a>
                <a href="" class="btn btn-sm mx-1 btn-success float-right"> <i class="fas fa-file-excel"></i> Exportar</a>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="tabela_de_balancetes">
                  <thead>
                    <tr>
                      <th>Conta</th>
                      <th>Descrição</th>
                      <th>Mov. Débito</th>
                      <th>Mov. Crédito</th>
                      <th>Saldo. Devedor</th>
                      <th>Saldo. Credor</th>
                    </tr>
                    
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Soma Saldos</th>
                      <th>{{ formatValor(0) }}</th>
                      <th>{{ formatValor(0) }}</th>
                    </tr>
                    
                    <tr>
                      <th></th>
                      <th class="text-right">Soma Líquida</th>
                      <th>{{ formatValor(resultado.total_movimento_debito) }}</th>
                      <th>{{ formatValor(resultado.total_movimento_credito) }}</th>
                      <th>{{ formatValor(resultado.total_debito) }}</th>
                      <th>{{ formatValor(resultado.total_credito) }}</th>
                    </tr>
                    
                  </thead>
                  
                  <tbody>
              
                    <tr v-for="item in movimentos" :key="item">
                      <td>{{ item.subconta.numero }}</td>
                      <td>{{ item.subconta.designacao }}</td>
                      <td>{{ formatValor(item.debito) }}</td>
                      <td>{{ formatValor(item.credito) }}</td>
                      <td>{{ formatValor(item.debito == item.credito ? 0 : (item.debito > item.credito ? item.debito - item.credito : 0)) }}</td>
                      <td>{{ formatValor(item.credito == item.debito ? item.credito - item.debito : (item.credito > item.debito ? item.credito - item.debito : 0)) }}</td>
                    </tr>
                  </tbody>
                </table>
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
    'movimentos',
    'resultado',
    "exercicios",
    "periodos",
    "contas",
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
      operador_id: "",
      tipo_diario: "",
      tipo_documento: "",
      
      exercicio_id: "",
      periodo_id: "",
      data_inicio: "",
      data_final: "",
      conta_id: "",
      
      params: {},
    };
  },
  mounted() {
    this.exercicio_id = this.sessions_exercicio ? this.sessions_exercicio.id : "";
    
    $('#tabela_de_balancetes').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    });
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
      
    conta_id: function (val) {
      this.params.conta_id = val;
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
      this.$inertia.get("/balancetes", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
      
    order_by_codigo(){
      this.params.order_by = "numero";
      this.updateData();
    },    
    
    order_by_diario(){
      this.params.order_by = "diario";
      this.updateData();
    }, 
    
    order_by_documento(){
      this.params.order_by = "documento";
      this.updateData();
    },  
  
    imprimirPlano() {
      window.open("imprimir-movimentos");
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
  
  