<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-uppercase">Detalhes do Perfil <span class="text-info">{{ role.name }}</span> </h1>
                    </div>

                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="card-tools">
                                    <div class="input-group input-group-md" style="width: 300px">
                                        <input type="text" v-model="search" class="form-control form-control-sm  float-right" placeholder="Search" />
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table  id="carregarTabelaEstudantes" style="width: 100%" class="table-sm table_estudantes table-bordered table-striped table-responsive-sm table-responsive-md table-responsive-lg table-responsive-xl table-responsive-xxl">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Designação</th>
                                                <th>Create</th>
                                                <th>Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items" :key="item.id">
                                                <td>#</td>
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.created_at }}</td>
                                                <td>{{ item.updated_at }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <Link href="" class="text-secondary"> Total Registro: {{ items.total }}</Link>
                                <!-- <Paginacao
                                    :links="items.links"
                                    :prev="items.prev_page_url"
                                    :next="items.next_page_url"
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
    import Paginacao from "../../../Shared/Paginacao.vue";
    
    export default {
        props: ["items", "role"],
        components: { Paginacao },
        data() {
            return {
                itemId: null,
            };
        },
        computed: {
            formTitle() {
                return this.isUpdate ? "Editar Instituição Com Renúncia" : "Adicionar Instituição Com Renúncia";
            }
        },
        
        mounted(){
            this.getBolsasEstudos();
        },
        
        watch: {
            options: function(val) {
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
            search: function(val) {
              this.params.search = val;
              this.updateData();
            }
        },
        
        methods: {
            
            updateData() {
                this.$Progress.start();
                this.$inertia.get("/roles.index", this.params, {
                    preserveState: true,
                    preverseScroll: true,
                    onSuccess: () => {
                        this.$Progress.finish();
                    }
              });
            },
        },
    }


</script>
