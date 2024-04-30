<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">NOTA DE CONTA</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/dashboard">Dashboard</a>
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
                            <!-- <div class="card-header">
                                <a @click="imprimirPlano()" class="btn-sm btn-danger btn float-left mx-1"><i
                                        class="fas fa-file-pdf"></i> Visualizar Sub-Contas</a>
                            </div> -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="tabela_de_balancetes"
                                    v-if="(count == 3) || (count == 0)">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th colspan="2" class="text-center">Saldo</th>
                                        </tr>
                                        <tr class="btn-dark">
                                            <th class="text-uppercase"
                                                v-if="(subcontas[0].id != 26) && (subcontas[0].id != 33)">SUBCONTAS DA
                                                CONTA Nº {{ subcontas[0].conta.numero }}</th>
                                            <th class="text-uppercase" v-else>SUBCONTAS DA CONTA Nº 11 e 12</th>

                                            <th>Devedor</th>
                                            <th>Credor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="subContas in subcontas1" :key="subContas">
                                            <tr class="btn-light">

                                                <td style="padding-left: 60px">
                                                    {{ subContas.numSubConta }}
                                                </td>
                                                <td style="padding-left: 60px; color: blue;">
                                                    <!-- {{ subContas }} -->
                                                    {{ subContas.debito ? (subContas.debito < subContas.credito ? '-' :
                                        formatarValorMonetario(subContas.debito)) : '-' }} </td>
                                                <td style="padding-left: 60px; color: red;">
                                                    <!-- {{ subContas.credito }} -->
                                                    {{ subContas.credito ? (subContas.debito > subContas.credito ? '-' :
                                        formatarValorMonetario(subContas.credito)) : '-' }}
                                                    <!-- {{ resultado_por_conta.total_credito }} -->
                                                </td>
                                            </tr>
                                        </template>

                                    </tbody>
                                </table>
                                <table class="table table-bordered table-hover" id="tabela_de_balancetes"
                                    v-else-if="count == 1">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th colspan="2" class="text-center">Saldo</th>
                                        </tr>
                                        <tr class="btn-dark">
                                            <th class="text-uppercase">CONTAS E SUBCONTAS REFERENTE A CLASSE DAS
                                                EXISTÊNCIAS</th>
                                            <th>Devedor</th>
                                            <th>Credor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="conta in contas" :key="conta">
                                            <tr class="btn-light">
                                                <th>{{ conta.numero }} - {{ conta.designacao }}</th>
                                            </tr>
                                            <tr class="btn-light" v-for="sub_contas in conta.subconta"
                                                :key="sub_contas">
                                                <td style="padding-left: 60px">
                                                    {{ sub_contas.numero }} -
                                                    {{ sub_contas.designacao }}
                                                </td>
                                                <td style="text-align: center;">
                                                    <span style="color: blue;"
                                                        v-for="movimentos in sub_contas.items_movimentos"
                                                        :key="movimentos">
                                                        {{ movimentos.debito }}
                                                    </span>
                                                </td>

                                                <td style="text-align: center;">
                                                    <span style="color: red;"
                                                        v-for="movimentos in sub_contas.items_movimentos"
                                                        :key="movimentos">
                                                        {{ movimentos.credito ? (movimentos.credito == 0 ? '-' :
                                                        movimentos.credito) : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>

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
    props: ["subcontas", "count", "contas", "subcontas1"],
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
        return {
            classe_designacao: "",
            conta_designacao: "",
            subconta_designacao: "",
            params: {},
        };
    },

    watch: {


    },
    mounted() {
        this.exercicio_id = this.sessions_exercicio
            ? this.sessions_exercicio.id
            : "";

        $("#tabela_de_balancetes").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
        });
    },
    methods: {

        imprimirPlano() {
            window.open("imprimir-plano");
        },
        formatarValorMonetario(valor) {
            // Converter o número para uma string e separar parte inteira da parte decimal
            let partes = String(valor).split(".");
            let parteInteira = partes[0];
            let parteDecimal = partes.length > 1 ? "." + partes[1] : "";

            // Adicionar separadores de milhar
            parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Retornar o valor formatado
            return parteInteira + parteDecimal;
        }

    },
};
</script>