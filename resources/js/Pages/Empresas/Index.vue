<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM  DE EMPRESA</h1>
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
              <div class="card-header"> 
                <a href="/empresas/create" class="btn btn-info"> <i class="fas fa-plus"></i> CRIAR EMPRESA</a>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>NIF</th>
                        <th>Nome</th>
                        <th>Regime do IVA</th>
                        <th>Moeda Base</th>
                        <!-- <th>Moeda Alternativo</th>
                        <th>Moeda Cámbio</th> -->
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>
                     <!-- {{ sessions ?  }}  -->
                    <tbody>
                      
                      <tr v-for="item in empresas.data" :key="item" :style="{ backgroundColor: verificar_sessao_empresa(item) ? '#D3D3D3' : '' }" >
                        <td class="text-uppercase">{{ item.codigo_empresa }}</td>
                        <td class="text-uppercase">{{ item.nome_empresa }}</td>
                        <td>{{ item.regime.designacao }}</td>
                        <td>{{ item.moeda.base.designacao }} - {{ item.moeda.base.sigla }}</td>
                        <!-- <td>{{ item.moeda.alternativa.designacao }} - {{ item.moeda.alternativa.sigla }}</td>
                        <td>{{ item.moeda.cambio.designacao }} - {{ item.moeda.cambio.sigla }}</td> -->
                        <td class="text-capitalize">{{ item.estado_empresa_id == 1 ? 'Activo': 'Desactivo' }}</td>
                        <td>
                                              
                          <div class="float-right">
                            <a :href="`/empresas/${item.id}/edit`" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Editar</a>
                            <a @click="mudar_estado_empresa(item)" class="btn btn-sm btn-info mx-1" v-if="item.estado_empresa_id == 2"><i class="fas fa-check"></i> Activar</a>
                            <a @click="mudar_estado_empresa(item)" class="btn btn-sm btn-danger mx-1" v-else><i class="fas fa-times"></i> Desctivar</a>
                            <a @click="iniciar_sessao_empresa(item)" class="btn btn-sm btn-secondary"><i class="fas fa-cog"></i> Operar</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ empresas.total }}</Link
                >
                <Paginacao
                  :links="empresas.links"
                  :prev="empresas.prev_page_url"
                  :next="empresas.next_page_url"
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
    'empresas'
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
    mudar_estado_empresa(item) {
      this.$Progress.start();

      axios.get(`/empresas/${item.id}`)
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
    
    verificar_sessao_empresa(item){
      return this.sessions && this.sessions.id == item.id ? true : false
    }
    
  },
};
</script>
  
  