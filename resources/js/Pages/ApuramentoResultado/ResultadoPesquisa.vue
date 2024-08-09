<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">APURAMENTO DE RESULTADOS Pesquisas</h1>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Listagem</li>
                        </ol>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="col-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="">
                                            <div class="row">

                                                <div class="col-12 col-md-3 mb-4">
                                                    <label for="exercicio_id" class="form-label">Exercícios</label>
                                                    <Select2 id="exercicio_id" v-model="exercicio_id"
                                                        :options="exercicios" :settings="{ width: '100%' }" />
                                                </div>

                                                <div class="col-12 col-md-3 mb-4">
                                                    <label for="periodo_id" class="form-label">Períodos</label>
                                                    <Select2 id="periodo_id" v-model="periodo_id" :options="periodos"
                                                        :settings="{ width: '100%' }" />
                                                </div>

                                                <div class="col-12 col-md-3 mb-4">
                                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                                    <input type="date" id="data_inicio" v-model="data_inicio"
                                                        class="form-control" placeholder="Ex: 1.1, 1.1.1"
                                                        :min="minDate(userYear)" :max="maxDate(userYear)" />
                                                </div>

                                                <div class="col-12 col-md-3 mb-4">
                                                    <label for="data_final" class="form-label">Final Inicio</label>
                                                    <input type="date" id="data_final" v-model="data_final"
                                                        class="form-control" placeholder="Ex: 1.1, 1.1.1"
                                                        :min="minDate(userYear)" :max="maxDate(userYear)" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a @click="imprimirBalancete()" class="btn btn-sm mx-1 btn-danger float-right">
                                    <i class="fas fa-file-pdf"></i> Visualizar</a>
                                <a href="" class="btn btn-sm mx-1 btn-success float-right"
                                    @click="ExportarExcelBalanco()">
                                    <i class="fas fa-file-excel"></i> Exportar Balanço</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Nº</th>
                                            <th rowspan="2">Conta</th>
                                            <th rowspan="2">Descrição</th>
                                            <th colspan="2" class="text-center">Dados do
                                                Período</th>
                                            <th colspan="2" class="text-center">Movimentos</th>
                                            <th colspan="2" class="text-center">Saldo</th>
                                        </tr>

                                        <tr>
                                            <th class="text-left">Débito</th>
                                            <th class="text-left">Crébito</th>
                                            <th class="text-left">Débito</th>
                                            <th class="text-left">Crédito</th>
                                            <th class="text-left">Devedor</th>
                                            <th class="text-left">Credor</th>
                                        </tr>

                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Total</th>
                                            <th class="text-primary text-right"
                                                v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        resultado_por_conta.total_debito == 0 ? '-' :
                                                            formatarValorMonetario(resultado_por_conta.total_debito) }}</th>
                                            <th class="text-danger text-right"
                                                v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        resultado_por_conta.total_credito == 0 ? '-' :
                                                            formatarValorMonetario(resultado_por_conta.total_credito) }}</th>
                                            <th class="text-primary text-right">{{ resultado_por_conta.total_debito == 0
                                                        ? '-' : formatarValorMonetario(resultado_por_conta.total_debito) }}</th>
                                            <th class="text-danger text-right">{{ resultado_por_conta.total_credito == 0
                                                        ? '-' : formatarValorMonetario(resultado_por_conta.total_credito) }}
                                            </th>
                                            <th class="text-primary text-right">{{ resultado_por_conta.total_debito >
                                                        resultado_por_conta.total_credito ?
                                                        formatarValorMonetario(resultado_por_conta.total_debito -
                                                            resultado_por_conta.total_credito) : '-' }}</th>
                                            <th class="text-danger text-right">{{ resultado_por_conta.total_credito >
                                                        resultado_por_conta.total_debito ?
                                                        formatarValorMonetario(resultado_por_conta.total_credito -
                                                            resultado_por_conta.total_debito) : '-' }}</th>
                                        </tr>

                                        <template v-for="(item, key) in registros" :key="key">
                                            <tr>
                                                <th colspan="3" class="text-uppercase">CLASSE {{ item.classe.numero ??
                                                        '' }} - {{ item.classe.designacao ?? '' }}</th>
                                                <th class="text-primary text-right">{{
                                                        item.conta.items_movimentos[0] ?
                                                            (item.conta.items_movimentos[0].TotalDebito == 0 ? '-' :
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito))
                                                            : '-' }}</th>
                                                <th class="text-danger text-right">{{
                                                        item.conta.items_movimentos[0] ?
                                                            (item.conta.items_movimentos[0].TotalCredito == 0 ? '-' :
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito))
                                                            : '-' }}</th>
                                                <th class="text-primary text-right">-</th>
                                                <th class="text-danger text-right">-</th>
                                                <th class="text-primary text-right">-</th>
                                                <th class="text-danger text-right">-</th>
                                            </tr>
                                            <tr>
                                                <th>Nº</th>
                                                <th class="text-uppercase" colspan="2">{{ item.conta.numero ?? '' }} -
                                                    {{ item.conta.designacao ?? '' }}</th>
                                                <th class="text-primary text-right"
                                                    v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        item.conta.items_movimentos[0] ?
                                                            (item.conta.items_movimentos[0].TotalDebito == 0 ? '-' :
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito))
                                                            : '-' }}</th>
                                                <th class="text-danger text-right"
                                                    v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        item.conta.items_movimentos[0] ?
                                                            (item.conta.items_movimentos[0].TotalCredito == 0 ? '-' :
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito))
                                                            : '-' }}</th>
                                                <th class="text-primary text-right">{{
                                                        item.conta.items_movimentos[0].TotalDebito == 0 ? '-' :
                                                            formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito)
                                                    }}</th>
                                                <th class="text-danger text-right">{{
                                                            item.conta.items_movimentos[0].TotalCredito == 0 ? '-' :
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito)
                                                        }}</th>
                                                <th class="text-primary text-right">{{
                                                            item.conta.items_movimentos[0].TotalDebito == 0 ? '-' :
                                                                (item.conta.items_movimentos[0].TotalDebito >
                                                                    item.conta.items_movimentos[0].TotalCredito ?
                                                                    formatarValorMonetario(item.conta.items_movimentos[0].TotalDebito -
                                                                        item.conta.items_movimentos[0].TotalCredito) : '-') }}</th>
                                                <th class="text-danger text-right">{{
                                                        item.conta.items_movimentos[0].TotalCredito == 0 ? '-' :
                                                            (item.conta.items_movimentos[0].TotalCredito >
                                                                item.conta.items_movimentos[0].TotalDebito ?
                                                                formatarValorMonetario(item.conta.items_movimentos[0].TotalCredito -
                                                                    item.conta.items_movimentos[0].TotalDebito) : '-') }}</th>
                                            </tr>

                                            <template v-for="(item2, index) in item.sub_contas_empresa" :key="index">
                                                <tr>
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item2.numero ?? '' }}</td>
                                                    <td><a :href="`extratos-contas?subconta_id=${item2.id}`"
                                                            class="text-primary text-uppercase">{{ item2.designacao ??
                                                        '' }}</a></td>
                                                    <td class="text-primary  text-right"
                                                        v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        item2.items_movimentos[0] ?
                                                            (item2.items_movimentos[0].total_debito == 0 ? '-' :
                                                                formatarValorMonetario(item2.items_movimentos[0].total_debito))
                                                            : '-' }}</td>
                                                    <td class="text-danger  text-right"
                                                        v-show="tipo_balancete_id == 3 || tipo_balancete_id == 1">{{
                                                        item2.items_movimentos[0] ?
                                                            (item2.items_movimentos[0].total_credito == 0 ? '-' :
                                                                formatarValorMonetario(item2.items_movimentos[0].total_credito))
                                                            : '-' }}</td>
                                                    <td class="text-primary  text-right">{{ item2.items_movimentos[0] ?
                                                        (item2.items_movimentos[0].total_debito == 0 ? '-' :
                                                            formatarValorMonetario(item2.items_movimentos[0].total_debito))
                                                        : '-' }}</td>
                                                    <td class="text-danger  text-right">{{ item2.items_movimentos[0] ?
                                                        (item2.items_movimentos[0].total_credito == 0 ? '-' :
                                                            formatarValorMonetario(item2.items_movimentos[0].total_credito))
                                                        : '-' }}</td>
                                                    <td class="text-primary  text-right">{{ item2.items_movimentos[0] ?
                                                        (item2.items_movimentos[0].total_debito >
                                                            item2.items_movimentos[0].total_credito ?
                                                            formatarValorMonetario(item2.items_movimentos[0].total_debito -
                                                                item2.items_movimentos[0].total_credito) : '-') : '-' }}</td>
                                                    <td class="text-danger  text-right">{{ item2.items_movimentos[0] ?
                                                        (item2.items_movimentos[0].total_credito >
                                                            item2.items_movimentos[0].total_debito ?
                                                            formatarValorMonetario(item2.items_movimentos[0].total_credito -
                                                                item2.items_movimentos[0].total_debito) : '-') : '-' }}</td>
                                                </tr>
                                            </template>
                                        </template>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
            </div>
        </div>

    </MainLayouts>
</template>

<script>
import Paginacao from "../../components/Paginacao.vue";

export default {
    props: [
        "exercicios", "contas_apuramento",
        "resultado", "resultado_por_conta",
        "periodos", "tipos_balancetes",
        "requests", "contas",
        "registros",
    ],
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
            exercicio_id: "",
            periodo_id: "",
            data_inicio: "",
            data_final: "",

            params: {},
        };
    },
    mounted() {

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

        exercicio_id: function (val) {
            this.params.exercicio_id = val;
            this.updateData();
        },

        data_inicio: function (val) {
            this.params.data_inicio = val;
            this.updateData();
        },

        data_final: function (val) {
            this.params.data_final = val;
            this.updateData();
        },

        periodo_id: function (val) {
            this.params.periodo_id = val;
            this.updateData();
        },

    },

    methods: {
        updateData() {
            this.$Progress.start();

            this.$inertia.get("/apuramento-resultado-pesquisa", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.$Progress.finish();
                },
            });
        },
        formatValor(atual) {
            const valorFormatado = Intl.NumberFormat("pt-br", {
                style: "currency",
                currency: "AOA",
            }).format(atual);
            return valorFormatado;
        },

        minDate(year) {
            return `${year}-01-01`; // Primeiro dia do ano especificado
        },

        maxDate(year) {
            return `${year}-12-31`; // Último dia do ano especificado
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
        },
    },
};
</script>
