<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">EDITAR CONTAS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/contas">Listagem</a></li>
                            <li class="breadcrumb-item active">Editar contas</li>
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
                                        <div class="col-12 col-md-6 mb-4">
                                            <label for="designacao" class="form-label">Província</label>
                                            <input type="text" id="designacao" v-model="form.designacao"
                                                class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.designacao">{{ form.errors.designacao }}</span>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <label for="pais_id" class="form-label">País</label>
                                            <Select2 v-model="form.pais_id" id="pais_id" class="col-12 col-md-12"
                                                :options="paises" :settings="{ width: '100%' }" />
                                            <span class="text-danger" v-if="form.errors && form.errors.pais_id">{{ form.errors.pais_id }}</span>
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
    props: ["provincia", "paises"],
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
        return {
            estados: [
                { 'id': "activo", 'text': "Activo" },
                { 'id': "desactivo", 'text': "Desactivo" },
            ],

            form: {
                designacao: this.provincia.designacao ?? "",
                pais_id: this.provincia.pais_id ?? "",
                itemId: this.provincia.id ?? "",
            },
        };
    },
    mounted() { },
    methods: {

        submit() {
            this.$Progress.start();
            
            axios
                .put(`/provincia/${this.form.itemId}`, this.form)
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
                        title: "Ocorreu um erro ao salvar os dados!",
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