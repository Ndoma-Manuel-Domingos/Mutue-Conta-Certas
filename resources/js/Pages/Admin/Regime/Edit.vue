<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">EDITAR REGIME</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/regime-empresa">Listagem</a></li>
                            <li class="breadcrumb-item active">Editar classes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <form @submit.prevent="submit">
                            <div class="card">
                                <div class="card-header"></div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-12 mb-4">
                                            <label for="numero" class="form-label">Descrição do Regime</label>
                                            <input type="text" id="numero" v-model="form.designacao"
                                                class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.classe_id">{{ form.errors.classe_id }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-success">
                                        <i class="fas fa-save"></i> Salvar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </MainLayoutsAdmin>
</template>

<script>

import MainLayoutsAdmin from '../../Layouts/MainLayoutsAdmin.vue';

export default {
    props: ["regime"],
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        sessions() {
            return this.$page.props.sessions.empresa_sessao;
        },
    },
    components: { MainLayoutsAdmin },
    data() {
        return {
            estados: [
                { 'id': "activo", 'text': "Activo" },
                { 'id': "desactivo", 'text': "Desactivo" },
            ],

            form: {
                designacao: this.regime.designacao ?? "",
                estado: "Activo",
                itemId: this.regime.id
            },
        };
    },
    mounted() { },
    methods: {
        submit() {
            this.$Progress.start();
            axios
                .put(`/regime-empresa/${this.form.itemId}`, this.form)
                .then((response) => {
                    // this.form.reset();
                    this.$Progress.finish();

                    Swal.fire({
                        toast: true,
                        icon: "success",
                        title: "Dados Salvos com Sucesso!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                    });

                    window.location.reload();
                    console.log("Resposta da requisição POST:", response.data);
                })
                .catch((error) => {
                    // sweetError("Ocorreu um erro ao actualizar Instituição!");
                    this.$Progress.fail();
                    Swal.fire({
                        toast: true,
                        icon: "danger",
                        title: "Correu um erro ao salvar os dados!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                    });

                    console.error("Erro ao fazer requisição POST:", error);
                });
        },
    },
};
</script>