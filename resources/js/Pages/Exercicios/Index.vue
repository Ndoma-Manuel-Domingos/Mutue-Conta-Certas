<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAM EXERCÍCIOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/exercicios">Dashboard</a></li>
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
                <a href="/exercicios/create" class="btn btn-info btn-sm mx-1"> <i class="fas fa-plus"></i> CRIAR EXERCÍCIO</a>

                <button class="btn btn-danger btn-sm mx-1" @click="imprimirContas()">
                  <i class="fas fa-save"></i> Imprimir Contas
                </button>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Designação</th>
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <tr v-for="item in exercicios.data" :key="item" :style="{ backgroundColor: verificar_sessao_exercicio(item) ? '#D3D3D3' : '' }" >
                        <td>#</td>
                        <td>{{ item.designacao }}</td>
                        <td class="text-capitalize">{{ item.estado == 1 ? 'Activo' : 'Desactivo' }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/exercicios/${item.id}/edit`" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Editar</a>
                            <a @click="mudar_estado_exercicio(item)" class="btn btn-sm btn-info mx-1" v-if="item.estado == '2'"><i class="fas fa-check"></i> Activar</a>
                            <a @click="mudar_estado_exercicio(item)" class="btn btn-sm btn-danger mx-1" v-else><i class="fas fa-times"></i> Desctivar</a>
                            <a @click="iniciar_sessao_exercicio(item)" class="btn btn-sm btn-secondary"><i class="fas fa-cog"></i> Operar</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ exercicios.total }}</Link
                >
                <Paginacao
                  :links="exercicios.links"
                  :prev="exercicios.prev_page_url"
                  :next="exercicios.next_page_url"
                />
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
    'exercicios'
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
    return {};
  },
  mounted() {},
  methods: {
    mudar_estado_exercicio(item) {
      this.$Progress.start();

      axios.get(`/exercicios/${item.id}`)
        .then((response) => {
          this.$Progress.finish();
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Estado Alterado com sucesso!",
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
    
    iniciar_sessao_exercicio(item) {
      this.$Progress.start();

      axios.get(`/exercicios/iniciar-sessão/${item.id}`)
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
    
    verificar_sessao_exercicio(item){
      return this.sessions_exercicio && this.sessions_exercicio.id == item.id ? true : false
    },

    imprimirContas() {
      window.open("imprimir-exercicios");
    },
  },
};
</script>
  
  