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
                <a href="/empresas/create" class="btn btn-info btn-sm"> <i class="fas fa-plus"></i> CRIAR EMPRESA</a>
                <button class="btn btn-danger btn-sm mx-1" >
                  <i class="fas fa-file-pdf"></i> Imprimir
                </button>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="tabela_de_empresas">
                  <thead>
                    <tr>
                      <th>NIF</th>
                      <th>Nome</th>
                      <th>Regime do IVA</th>
                      <th>Moeda Base</th>
                      <th>Moeda Alternativo</th>
                      <th>Tipo</th>
                      <th>Grupo</th>
                      <th>Estado</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <tr v-for="item in empresas" :key="item" :style="{ backgroundColor: verificar_sessao_empresa(item) ? '#D3D3D3' : '' }" >
                      <td class="text-uppercase"><a :href="`/empresas/${item.id}`">{{ item.codigo_empresa ?? '' }}</a></td>
                      <td class="text-uppercase">{{ item.nome_empresa ?? '-' }}</td>
                      <td>{{ item.regime ? item.regime.designacao : '-' }}</td>
                      <td>{{ item.moeda ? (item.moeda.base ? (item.moeda.base ? item.moeda.base.designacao : "") : "") : "" }} - {{ item.moeda ? (item.moeda.base ? (item.moeda.base ? item.moeda.base.sigla : "") : "") : "" }}</td>
                      <td>{{ item.moeda ? (item.moeda.base ? (item.moeda.alternativa ? item.moeda.alternativa.designacao : "") : "") : "" }} - {{ item.moeda ? (item.moeda.alternativa ? (item.moeda.alternativa ? item.moeda.alternativa.sigla : "") : "") : "" }}</td>
                      
                      <td>{{ item.tipo ? item.tipo.designacao : "" }}</td>
                      <td>{{ item.grupo ? item.grupo.designacao : "" }}</td>
                      <td class="text-capitalize">{{ item.estado_empresa_id == 1 ? 'Activo': 'Desactivo' }}</td>
                      <td>
                                            
                        <div class="float-right">
                          <a :href="`/empresas/${item.id}/edit`" class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i> Editar</a>
                          <a @click="mudar_estado_empresa(item)" class="btn btn-sm btn-info mx-1" v-if="item.estado_empresa_id == 2"><i class="fas fa-check"></i> Activar</a>
                          <a @click="mudar_estado_empresa(item)" class="btn btn-sm btn-danger mx-1" v-else><i class="fas fa-times"></i> Desctivar</a>
                          <a @click="iniciar_sessao_empresa(item)" class="btn btn-sm btn-secondary mx-1"><i class="fas fa-cog"></i> Operar</a>
                        </div>
                      </td>
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
    sessions_exercicio() {
      return this.$page.props.sessions.exercicio_sessao;
    },
  },
  data() {
    return {
      input_busca_empresas: "",
      params: {},
    };
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
  },
  mounted() {
    $('#tabela_de_empresas').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    });
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/empresas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    mudar_estado_empresa(item) {
      this.$Progress.start();

      axios.get(`/empresas-mudar-estado/${item.id}`)
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
            title: "Sem permissão para operar com está empresa!",
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
  
  