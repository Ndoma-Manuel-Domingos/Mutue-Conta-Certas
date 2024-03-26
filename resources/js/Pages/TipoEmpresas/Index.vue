<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">LISTAGEM DE TIPOS DE EMPRESAS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/tipos-empresas">Dashboard</a></li>
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
                                <a href="/tipos-empresas/create" class="btn btn-info btn-sm"> <i class="fas fa-plus"></i> CRIAR TIPO DE EMPRESA </a>
                                <button class="btn btn-sm btn-danger mx-1">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </button>
                                
                                <div class="card-tools">
                                  <div class="input-group input-group" style="width: 450px">
                                    <input
                                      type="text"
                                      v-model="input_busca_tipos_empresas"
                                      class="form-control float-right"
                                      placeholder="Informe a campo"
                                    />
                                    <div class="input-group-append">
                                      <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Designação</th>
                                                <th class="text-right">Ações</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="item in tipos_empresas.data" :key="item">
                                                <td>#</td>
                                                <td>{{ item.id }}</td>
                                                <td>{{ item.designacao }}</td>
                                                <td>
                                                    <div class="float-right">
                                                        <a :href="`/tipos-empresas/${item.id}/edit`"
                                                            class="btn btn-sm btn-success"><i class="fas fa-edit"></i>
                                                            Editar</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <Link href="" class="text-secondary">
                                Total Registro: {{ tipos_empresas.total }}</Link>
                                <Paginacao :links="tipos_empresas.links" 
                                    :prev="tipos_empresas.prev_page_url"
                                    :next="tipos_empresas.next_page_url" 
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
        'tipos_empresas',
    ],
    components: {
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
            input_busca_tipos_empresas: "",
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
        
        input_busca_tipos_empresas: function (val) {
          this.params.input_busca_tipos_empresas = val;
          this.updateData();
        },
    
    },
    
    mounted() { },
    methods: {
            
        updateData() {
          this.$Progress.start();
          this.$inertia.get("/tipos-empresas", this.params, {
            preserveState: true,
            preverseScroll: true,
            onSuccess: () => {
              this.$Progress.finish();
            },
          });
        },
    
        
        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>