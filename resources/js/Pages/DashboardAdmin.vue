<template>
    <MainLayoutsAdmin>

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">DASHBOARD</h1>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-12 col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <p><i class="fa fa-university" aria-hidden="true"> TOTAL DE EMPRESAS ({{ empresas_count }})</i></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="/listar-empresas" class="small-box-footer">Mais detalhe <i
                                class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <p>LICENÇAS EM UTILIZAÇÃO</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="licenca-utilizacao" class="small-box-footer">Mais detalhe <i
                                class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12 col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <p>LICENÇAS SEM UTILIZAÇÃO</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="licenca-sem-utilizacao" class="small-box-footer">Mais detalhe<i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

            </div>
        </div>

    </MainLayoutsAdmin>
</template>

<script>
import MainLayoutsAdmin from './Layouts/MainLayoutsAdmin.vue'
export default {
    props:[
        'empresas_count'
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
    components: { MainLayoutsAdmin },
    data() {
        return {};
    },
    mounted() {
    },
    methods: {
    },
};
</script>
