<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">LISTAGEM DOS PAÍSES</h1>
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
                                <a href="/paises/create" class="btn btn-info btn-sm">
                                    <i class="fas fa-plus"></i> CRIAR NOVO PAÍS</a>

                                <!-- <button
                  class="btn btn-danger btn-sm float-right"
                  @click="imprimirPeriodos()"
                >
                  <i class="fas fa-save"></i> Visualizar
                </button> -->

                                <a href="" class="btn btn-sm mx-1 btn-success float-right" @click="ExportarExcelPais()">
                                    <i class="fas fa-file-excel"></i> Exportar</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table table-bordered table-hover" id="tabela_paises">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Designação</th>
                                                <th>Designação em Inglês</th>
                                                <th class="text-right">Ações</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="item in paises" :key="item">
                                                <td>#</td>
                                                <td>{{ item.id }}</td>
                                                <td>{{ item.designacao }}</td>
                                                <td>{{ item.nome_ingles }}</td>
                                                <td>
                                                    <div class="float-right">
                                                        <a :href="`/paises/${item.id}/edit`"
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
    props: ["paises"],
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
        $('#tabela_paises').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    methods: {
        deleteItem(item) {
            console.log(item.id);
        },
        imprimirPeriodos() {
            window.open("imprimir-paises");
        },

        ExportarExcelPais() {
            window.open(
                `exportar-pais-excel`
            );
        },
    },
};
</script>
