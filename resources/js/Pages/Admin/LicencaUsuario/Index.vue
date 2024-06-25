<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">Associar Usuário a uma Licença</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/administrativo">Dashboard</a></li>
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
                                <a href="" class="btn btn-sm mx-1 btn-success float-right"> <i
                                        class="fas fa-file-excel"></i> Exportar</a>
                                <a href="/licenca-usuario/create" class="btn btn-info"> <i class="fas fa-plus"></i>Nova
                                    Associação </a>
                            </div>

                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="table_licencas">

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Licença</th>
                                            <th>Usuário</th>
                                            <th class="text-right" style="width: 270px;">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <tr v-for="item in licenca_data" :key="item">
                                            <td>{{ item.id }}</td>
                                            <td v-for="licenca1 in item.licenca" :key="licenca1" >{{ licenca1.titulo }}
                                            </td>
                                            <td v-for="usuario1 in item.usuario" :key="usuario1" > {{ usuario1.name }}
                                            </td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/licenca-usuario/${item.id}/edit`"
                                                        class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i>
                                                        Editar</a>
                                                    <a class="btn btn-sm btn-secondary mx-1" @click="mudar_estado(item)"
                                                        v-if="item.estado == 1"><i class="fas fa-cog"></i>
                                                        Desactivar</a>

                                                    <a class="btn btn-sm btn-info mx-1" @click="mudar_estado(item)"
                                                        v-else><i class="fas fa-cog"></i>
                                                        Activar</a>
                                                    <a @click="deleteItem(item)" class="btn btn-sm btn-danger mx-1"><i
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
        'licenca_data',
        'licenca_em_utilizacao',
        'licenca_sem_utilizacao',
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
        $('#table_licencas').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        deleteItem(item) {

            axios.delete(`/licenca/${item.id}`)
                .then((response) => {
                    this.$Progress.finish();
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        title: "Modulo Eliminado com Sucesso!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                    window.location.reload();
                })
                .catch((error) => {

                    this.$Progress.fail();
                    Swal.fire({
                        toast: true,
                        icon: "danger",
                        title: "Ocorreu um erro ao tantar eliminar o modulo!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                });

            console.log(item.id)
        },

        mudar_estado(item) {

            this.$Progress.start();

            axios.get(`/mudar-estado-licenca/${item.id}`)
                .then((response) => {
                    this.$Progress.finish();
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        title: "Estado Alterado com sucesso!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                    window.location.reload();
                })
                .catch((error) => {

                    this.$Progress.fail();
                    Swal.fire({
                        toast: true,
                        icon: "danger",
                        title: "Correu um erro ao Estado Alterado com sucesso!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                });
        },

        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },

        ExportarExcelMunicipio() {
            window.open(
                `exportar-municipio-excel`
            );
        },
    },
};
</script>
