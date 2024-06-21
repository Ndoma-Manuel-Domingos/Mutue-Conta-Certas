<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE TIPOS DE EMPRESAS</h1>
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
                                <a href="/tipos-empresas/create" class="btn btn-info btn-sm"> <i  class="fas fa-plus"></i> CRIAR TIPO DE EMPRESA </a>
                                        
                                <button class="btn btn-sm btn-danger mx-1">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </button>

                                <a href="" class="btn btn-sm mx-1 btn-success float-left"
                                    @click="ExportarExcelTipoEmpresa()">
                                    <i class="fas fa-file-excel"></i> Exportar</a>

                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="tabela_tipo_de_empresas">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Designação</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in tipos_empresas" :key="item">
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
                    </div>
                </div>
            </div>
        </div>
        
    </MainLayoutsAdmin>
</template>

<script>

import MainLayoutsAdmin from '../../Layouts/MainLayoutsAdmin.vue';

export default {
    props: [
        'tipos_empresas',
    ],
    components: { MainLayoutsAdmin },
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
        $('#tabela_tipo_de_empresas').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
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

        ExportarExcelTipoEmpresa() {
            window.open(
                `exportar-tipo-empresa-excel`
            );
        },

        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>
