<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">DEMONSTRAÇÃO DE FLUXO DE CAIXA DETALHADO</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/demonstracao-fluxo-caixa">Fluxo de Caixa</a>
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
                                <a href="" class="btn btn-sm mx-1 btn-success float-right"
                                    @click="ExportarExcelDemonstracaoDetalhes()">
                                    <i class="fas fa-file-excel"></i> Exportar Balanço</a>
                            </div>
                            <div class="card-body">

                                <table class="table table-bordered" style="width: 100%" id="tabela_de_diarias">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nº</th>
                                            <th class="text-center" style="width: 100px;">Nº Mov</th>
                                            <th>Descrição</th>
                                            <th class="text-right">Débito</th>
                                            <th class="text-right">Crédito</th>
                                            <th class="text-right">Saldo</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(item, index) in item_movimentos" :key="index">
                                            <td class="text-center">{{ index + 1 }} </td>
                                            <td class="text-center">{{ item.movimento ? item.movimento.lancamento_atual
                                        : "" }}</td>
                                            <td class="text-left">{{ item.descricao }} </td>
                                            <td class="text-right text-primary">{{ formatarValorMonetario(item.debito)
                                                }} </td>
                                            <td class="text-right text-danger">{{ formatarValorMonetario(item.credito)
                                                }} </td>

                                            <td class="text-right text-primary" v-if="item.debito > item.credito">{{
                                        formatarValorMonetario(item.debito - item.credito) }}</td>
                                            <td class="text-right text-danger" v-if="item.credito > item.debito">{{
                                        formatarValorMonetario(item.credito - item.debito) }}</td>

                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayouts>
</template>

<script>
export default {
    props: [
        "item_movimentos",
    ],
    components: {},
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
            params: {},
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
        tipo_credito_id: function (val) {
            this.params.tipo_credito_id = val;
            this.updateData();
        },
    },

    methods: {
        updateData() {
            this.$Progress.start();
            this.$inertia.get("/fluxos-caixas/create", this.params, {
                preserveState: true,
                preverseScroll: true,
                onSuccess: () => {
                    this.$Progress.finish();
                },
            });
        },

        ExportarExcelDemonstracaoDetalhes() {
            window.open(
                `exportar-demonstracao-detalhe-excel`
            );
        },

    
        formatarValorMonetario(valor) {
          // Converte o valor para uma string com duas casas decimais
          let valorFormatado = Number(valor).toFixed(2);
      
          // Separa a parte inteira da parte decimal
          let partes = valorFormatado.split(".");
          let parteInteira = partes[0];
          let parteDecimal = partes[1] ? "," + partes[1] : "";
      
          // Adiciona separadores de milhar
          parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      
          // Retorna o valor formatado
          return parteInteira + parteDecimal;
        },

    },
};
</script>
