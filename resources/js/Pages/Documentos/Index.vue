<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">DOCUMENTOS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/documentos">Dashboard</a>
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
                                <a href="/documentos/create" class="btn btn-info btn-sm mx-1">
                                    <i class="fas fa-plus"></i> CRIAR DOCUMENTOS</a>
                                <button class="btn btn-danger btn-sm mx-1" @click="imprimirPeriodos()">
                                    <i class="fas fa-file-pdf"></i> Imprimir
                                </button>

                                <a href="" class="btn btn-sm mx-1 btn-success float-left"
                                    @click="ExportarExcelDocumento()">
                                    <i class="fas fa-file-excel"></i> Exportar</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="tabela_de_movimentos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Designação</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in documentos" :key="item">
                                            <td>#</td>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.designacao }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/documentos/${item.id}/edit`"
                                                        class="btn btn-sm btn-success"><i class="fas fa-edit"></i>
                                                        Editar</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">

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
    props: ["documentos"],
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
    },
    data() {
        return {};
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
        $('#tabela_de_movimentos').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        updateData() {
            this.$Progress.start();
            this.$inertia.get("/documentos", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.$Progress.finish();
                },
            });
        },

        ExportarExcelDocumento() {
            window.open(
                `exportar-documento-excel`
            );
        },

        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>
