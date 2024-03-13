<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAM CLASSES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/classes">Dashboard</a></li>
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
                <a href="/classes/create" class="btn btn-info"> <i class="fas fa-plus"></i> CRIAR CLASSES</a>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <tr v-for="item in classes.data" :key="item">
                        <td>#</td>
                        <td>{{ item.classe.numero }}</td>
                        <td>{{ item.classe.designacao }}</td>
                        <td class="text-capitalize">{{ item.estado }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/classes/${item.id}/edit`" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Editar</a>
                            <a @click="deleteItem(item)" class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i> Apagar</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ classes.total }}</Link
                >
                <Paginacao
                  :links="classes.links"
                  :prev="classes.prev_page_url"
                  :next="classes.next_page_url"
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
    'classes'
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
    deleteItem(item) {
      console.log(item.id)
    },
  },
};
</script>
  
  