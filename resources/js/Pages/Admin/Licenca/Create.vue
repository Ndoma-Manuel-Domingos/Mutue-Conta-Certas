<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">CRIAR NOVA LICENÇA</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/licenca">Listagem</a></li>
                            <li class="breadcrumb-item active">Criar Licença</li>
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
                                    <h6>Preencha todos os campos abaixo</h6>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="titulo" class="form-label">Título</label>
                                            <input type="text" placeholder="Informe o titulo" id="titulo" v-model="form.titulo" class="form-control">
                                            <span class="text-danger"  v-if="form.errors && form.errors.titulo">{{ form.errors.titulo }}</span>
                                        </div>
                                        
                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="preco" class="form-label">Preço</label>
                                            <input type="text" placeholder="Informe o titulo" id="preco" v-model="form.preco" class="form-control">
                                            <span class="text-danger"  v-if="form.errors && form.errors.preco">{{ form.errors.preco }}</span>
                                        </div>

                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="designacao" class="form-label">Descrição</label>
                                            <input type="text" placeholder="Informe uma descrição" id="designacao" v-model="form.designacao" class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.designacao">{{ form.errors.designacao }}</span>
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
    props: [
        "modulos",
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
        return {

            estados: [
                { 'id': "activo", 'text': "Activo" },
                { 'id': "desactivo", 'text': "Desactivo" },
            ],

            form: {
                titulo: "",
                preco: "",
                designacao: "",
            },
        };
    },
    mounted() { },
    methods: {
        submit() {
            this.$Progress.start();

            axios.post(route('licenca.store'), this.form)
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
                        timer: 4000
                    })

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
                        timer: 4000
                    })

                    console.error("Erro ao fazer requisição POST:", error);
                });
        },
    },
};
</script>
