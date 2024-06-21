<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE PROVÍNCIAS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/provincia">Dashboard</a></li>
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

                                <a href="" class="btn btn-sm mx-1 btn-success float-right"
                                    @click="ExportarExcelProvincia()">
                                    <i class="fas fa-file-excel"></i> Exportar</a>

                                <a href="/provincia/create" class="btn btn-info btn-sm"> <i class="fas fa-plus"></i>NOVA
                                    PROVÍNCIA </a>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="tabela_provincias">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Designação</th>
                                            <th>País</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in provincia" :key="item">
                                            <td>#</td>
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.designacao }}</td>
                                            <td>{{ item.pais.designacao }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/provincia/${item.id}/edit`"
                                                        class="btn btn-sm btn-success"><i class="fas fa-edit"></i>
                                                        Editar</a>
                                                    <a @click="deleteItem(item)"
                                                        class="btn btn-sm btn-danger mx-1"><i
                                                            class="fas fa-trash"></i> Apagar</a>
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
        'provincia'
    ],
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
        return {};
    },
    mounted() {
        $('#tabela_provincias').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        deleteItem(item) {
            console.log(item.id)
        },

        ExportarExcelProvincia() {
            window.open(
                `exportar-provincia-excel`
            );
        },
        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>
