<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE REGIMES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/regimes-empresa">Dashboard</a>
                            </li>
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
                                <a href="/regime-empresa/create" class="btn btn-info btn-sm mx-1">
                                    <i class="fas fa-plus"></i> CRIAR REGIME</a>
                                <button class="btn btn-danger btn-sm mx-1" @click="imprimirPeriodos()">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </button>

                                <a href="" class="btn btn-sm mx-1 btn-success float-left"
                                    @click="ExportarExcelRegima()">
                                    <i class="fas fa-file-excel"></i> Exportar</a>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="tabela">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Designação</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in regimes.data" :key="item">
                                            <td>#</td>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.designacao }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/regime-empresa/${item.id}/edit`"
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
    props: ["regimes"],
    components: { MainLayoutsAdmin },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        sessions() {
            return this.$page.props.sessions.empresa_sessao;
        },
    },
    data() {
        return {
            input_busca_regimes: "",
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

        input_busca_regimes: function (val) {
            this.params.input_busca_regimes = val;
            this.updateData();
        },
    },
    mounted() {
        $('#tabela').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        updateData() {
            this.$Progress.start();
            this.$inertia.get("/regime-empresa", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.$Progress.finish();
                },
            });
        },

        ExportarExcelRegima() {
            window.open(
                `exportar-regime-excel`
            );
        },


        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>
