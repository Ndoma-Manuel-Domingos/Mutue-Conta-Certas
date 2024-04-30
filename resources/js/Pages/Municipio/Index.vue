<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE MUNICÍPIOS</h1>
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
                                <a href="/municipio/create" class="btn btn-info"> <i class="fas fa-plus"></i>NOVO
                                    MUNICÍPIO </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table table-bordered table-hover" id="tabela_municipios">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Designação</th>
                                                <th>Província</th>
                                                <th class="text-right">Ações</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="item in municipio" :key="item">
                                                <td>#</td>
                                                <td>{{ item.id }}</td>
                                                <td>{{ item.designacao }}</td>
                                                <td>{{ item.provincia.designacao }}</td>
                                                <td>
                                                    <div class="float-right">
                                                        <a :href="`/municipio/${item.id}/edit`"
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
        </div>
    </MainLayouts>
</template>

<script>

import Paginacao from "../../components/Paginacao.vue";

export default {

    props: [
        'municipio'
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
    },
    data() {
        return {};
    },
    mounted() {
        $('#tabela_municipios').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        deleteItem(item) {
            console.log(item.id)
        },
        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },
    },
};
</script>