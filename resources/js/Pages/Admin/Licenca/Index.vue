<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DE LICENÇAS</h1>
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
                                <a href="" class="btn btn-sm mx-1 btn-success float-right"> <i class="fas fa-file-excel"></i> Exportar</a>
                                <a href="/licenca/create" class="btn btn-info"> <i class="fas fa-plus"></i>NOVA LICENÇAS </a>
                            </div>
                            <div class="card-body table-responsive">
                                <t  able class="table table-bordered table-hover" id="table_licencas">

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Título</th>
                                            <th>Preço</th>
                                            <th>Módulos</th>
                                            <th>Designação</th>
                                            <th class="text-right" style="width: 270px;">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in licenca" :key="item">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.titulo }}</td>
                                            <td>{{ item.preco }}</td>
                                            <td>
                                                <div v-for="modu in item.modulos" :key="modu">
                                                    <span class="small">{{ modu ? (modu.modulo ? modu.modulo.designacao : '') : '' }} /</span>
                                                </div>
                                            </td>
                                            <td>{{ item.designacao }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/licenca/${item.id}/edit`"
                                                        class="btn btn-sm btn-success mx-1"><i class="fas fa-edit"></i> Editar</a>
                                                    <a :href="`/licenca/${item.id}`"
                                                        class="btn btn-sm btn-info mx-1"><i class="fas fa-cog"></i> Configurar</a>
                                                    <a @click="deleteItem(item)"
                                                        class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i> Apagar</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </t>
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
        'licenca'
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
