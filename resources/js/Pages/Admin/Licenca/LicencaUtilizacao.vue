<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE LICENÇAS EM UTILIZAÇÃO</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Dashboard</a></li>
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
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="table_licencas">

                                    <thead>
                                        <tr>

                                            <th>Título</th>
                                            <th>Preço</th>
                                            <th>Módulos</th>
                                            <th>Designação</th>
                                            <th>Utilizador</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <!-- {{ licenca_em_utilizacao }} -->
                                        <tr v-for="item in licenca_em_utilizacao" :key="item">
                                            <td>
                                                <div v-for="licenca in item.licenca" :key="licenca">
                                                    <span class="small">{{ licenca.titulo }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div v-for="licenca in item.licenca" :key="licenca">
                                                    <span class="small">{{ licenca.preco }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div v-for="licenca1 in item" :key="licenca1">
                                                    <div class="small" v-for="licenca2 in licenca1" :key="licenca2">
                                                        <div v-for="licenca3 in licenca2.modulos" :key="licenca3">
                                                            {{ licenca3.modulo.designacao }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div v-for="licenca in item.licenca" :key="licenca">
                                                    <span class="small">{{ licenca.designacao }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div v-for="usuario in item.usuario" :key="usuario">
                                                    <span class="small">{{ usuario.name }}</span>
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
        'licenca_em_utilizacao'
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
