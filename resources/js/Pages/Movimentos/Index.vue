<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM DE MOVIMENTOS</h1>
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
                <a href="/movimentos/create" class="btn btn-info"> <i class="fas fa-plus"></i> LANÇAR MOVIMENTOS</a>
                <a href="" class="btn btn-sm mx-1 btn-danger float-right"> <i class="fas fa-file-pdf"></i> Visualizar</a>
                <a href="" class="btn btn-sm mx-1 btn-success float-right"> <i class="fas fa-file-excel"></i> Exportar</a>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Nº</th>
                        <th>Diário</th>
                        <th>Documento</th>
                        <th>Débito</th>
                        <th>Crédito</th>
                        <th>Data</th>
                        <th>Exercício</th>
                        <th>Operador</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <tr v-for="item in movimentos.data" :key="item">
                        <td>{{ item.id }}</td>
                        <td>{{ item.diario.numero }} - {{ item.diario.designacao }}</td>
                        <td>{{ item.tipo_documento.numero }} - {{ item.tipo_documento.designacao }}</td>
                        <td>{{ formatValor(item.debito) }}</td>
                        <td>{{ formatValor(item.credito) }}</td>
                        <td>{{ item.data_lancamento }}</td>
                        <td>{{ item.exercicio.designacao }}</td>
                        <td>{{ item.criador.name }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/movimentos/${item.id}/edit`" class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i> Editar</a>
                            <a :href="`/movimentos/${item.id}`" class="btn btn-sm btn-info mx-1"><i class="fas fa-info-circle"></i> Detalhe</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ movimentos.total }}</Link
                >
                <Paginacao
                  :links="movimentos.links"
                  :prev="movimentos.prev_page_url"
                  :next="movimentos.next_page_url"
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
    'movimentos'
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
  
  