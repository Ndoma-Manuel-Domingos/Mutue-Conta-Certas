<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE CONTRAPARTIDAS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/contrapartidas">Dashboard</a></li>
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
                                <h3 class="card-title">
                                    <a href="/contrapartidas/create" class="btn btn-sm btn-info mx-1">
                                        <i class="fas fa-plus"></i> CRIAR CONTRAPARTIDA</a>
                                    <button class="btn btn-sm float-right btn-danger mx-1">
                                        <i class="fas fa-file-pd"></i> Imprimir
                                    </button>

                                    <a href="" class="btn btn-sm mx-1 btn-success float-right"
                                        @click="ExportarExcelContraPartida()">
                                        <i class="fas fa-file-excel"></i> Exportar Balanço</a>
                                </h3>

                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Conta</th>
                                            <th>Tipo Crédito</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in contrapartidas" :key="item">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.sub_conta.numero }} - {{ item.sub_conta.designacao }}</td>
                                            <td class="text-capitalize">{{ item.tipo_credito ?
                                            item.tipo_credito.designacao : '' }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/contrapartidas/${item.id}/edit`"
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
    </MainLayouts>
</template>

<script>
import Paginacao from "../../components/Paginacao.vue";

export default {
    props: ["contrapartidas"],
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
        return {};
    },
    mounted() {
        $('#tabela_de_diarias').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
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
    methods: {

        updateData() {
            this.$Progress.start();
            this.$inertia.get("/contrapartidas", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.$Progress.finish();
                },
            });
        },

        ExportarExcelContraPartida() {
            window.open(
                `exportar-contra-partida-excel`
            );
        },

    },
};
</script>
