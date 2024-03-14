<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM CONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/contas">Dashboard</a></li>
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
                <a href="/contas/create" class="btn btn-info"> <i class="fas fa-plus"></i> CRIAR CONTAS</a>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Conta</th>
                        <th>Classe</th>
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <tr v-for="item in contas.data" :key="item">
                        <td>#</td>
                        <td>{{ item.conta.numero }}</td>
                        <td>{{ item.conta.designacao }}</td>
                        <td>{{ item.classe.designacao }}</td>
                        <td class="text-capitalize">{{ item.estado }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/contas/${item.id}/edit`" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Editar</a>
                            
                            <a @click="mudar_estado(item)" class="btn btn-sm btn-info mx-1" v-if="item.estado == 'desactivo'"><i class="fas fa-check"></i> Activar</a>
                            <a @click="mudar_estado(item)" class="btn btn-sm btn-danger mx-1" v-else><i class="fas fa-times"></i> Desctivar</a>
                            
                            <!-- <a href="" class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i> Apagar</a> -->
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ contas.total }}</Link
                >
                <Paginacao
                  :links="contas.links"
                  :prev="contas.prev_page_url"
                  :next="contas.next_page_url"
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
    'contas'
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
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {
    mudar_estado(item) {
      this.$Progress.start();

      axios.get(`/contas/${item.id}`)
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
  },
};
</script>
  
  