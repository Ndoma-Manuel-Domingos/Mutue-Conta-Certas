<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">EDITAR LICENÇA</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/licenca">Listagem</a></li>
                            <li class="breadcrumb-item active">Editar LICENÇA</li>
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
                                <div class="card-header">
                                    <h6>Editar Licença, podes melhorar a licença!</h6>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="titulo" class="form-label">Título</label>
                                            <input type="text" id="titulo" v-model="form.titulo" class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.titulo">{{ form.errors.titulo }}</span>
                                        </div>

                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="preco" class="form-label">Preço</label>
                                            <input type="text" id="preco" v-model="form.preco" class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.preco">{{ form.errors.preco }}</span>
                                        </div>

                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="numero" class="form-label">Designação</label>
                                            <input type="text" id="numero" v-model="form.designacao"
                                                class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.classe_id">{{
                                                form.errors.classe_id }}</span>
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
    props: ["licenca", "modulos"],
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
                designacao: this.licenca.designacao ?? "",
                titulo: this.licenca.titulo ?? "",
                preco: this.licenca.preco ?? "",
                itemId: this.licenca.id ?? "",
            },
        };
    },
    mounted() { },
    methods: {

        submit() {
            this.$Progress.start();

            axios
                .put(`/licenca/${this.form.itemId}`, this.form)
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
