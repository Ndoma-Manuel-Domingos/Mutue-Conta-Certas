<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">OPERADORES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/operadores">Dashboard</a>
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
                                <h3 class="card-title">
                                    <a href="/operadores/create" class="btn btn-sm btn-info mx-1">
                                        <i class="fas fa-plus"></i> NOVOS OPERADORES</a>

                                    <a class="btn btn-sm float-right btn-danger mx-1" @click="imprimirPDF()">
                                        <i class="fas fa-file-pdf"></i> Imprimir
                                    </a>
                                    <a href="" class="btn btn-sm mx-1 btn-success float-right"
                                        @click="ExportarExcelOperador()">
                                        <i class="fas fa-file-excel"></i> Exportar</a>
                                </h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nome</th>
                                            <th>E.mail</th>
                                            <th>Empresa</th>
                                            <th rowspan="2" style="width: 300px"><span class="float-right">Ações</span>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(item, index) in operadores" :key="item">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ item.user.name }}</td>
                                            <td>{{ item.user.email }}</td>
                                            <td>{{ item.empresa.nome_empresa }}</td>

                                            <td>
                                                <div class="float-right">
                                                    <a :href="`/operadores/${item.id}`"
                                                        class="btn btn-sm btn-info mx-2"><i class="fas fa-info"></i>
                                                        Detalhe</a>
                                                    <a :href="`/operadores/${item.id}/edit`"
                                                        class="btn btn-sm btn-success mr-2"><i class="fas fa-edit"></i>
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
    props: ["operadores"],
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
        periodo_sessao() {
            return this.$page.props.sessions.periodo_sessao;
        },
    },
    data() {
        return {

        };
    },
    mounted() {

        $("#tabela_de_diarias").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
        });

    },
    watch: {
    },
    methods: {
        ExportarExcelOperador() {
      window.open(
        `exportar-operador-excel`
      );
},
    },
};
</script>
