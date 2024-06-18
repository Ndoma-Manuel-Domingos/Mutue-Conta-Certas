<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">DETALHE DA LICENÇA</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/licenca">Listagem</a></li>
                            <li class="breadcrumb-item active">DETALHE LICENÇA</li>
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
                                    <h6>Adicionar Mais Modulos a Licença</h6>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-4">
                                            <label for="titulo" class="form-label">Licença</label>
                                            <input type="text" disabled id="titulo" v-model="form.titulo" class="form-control">
                                            <span class="text-danger" v-if="form.errors && form.errors.titulo">{{ form.errors.titulo }}</span>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <label for="modulo_id" class="form-label">Módulos</label>
                                            <Select2 v-model="form.modulo_id" id="modulo_id" class="col-12 col-md-12"
                                                :options="modulos" :settings="{ width: '100%' }" />
                                            <span class="text-danger" v-if="form.errors && form.errors.modulo_id">{{ form.errors.modulo_id }}</span>
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
                
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" id="table_licencas">

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Designação</th>
                                            <th class="text-right">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="item in modulos_licenca" :key="item">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.modulo.designacao }}</td>
                                            <td>
                                                <div class="float-right">
                                                    <a @click="deleteItem(item)"
                                                        class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i> Apagar</a>
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
        
    </MainLayoutsAdmin>
</template>

<script>
import MainLayoutsAdmin from '../../Layouts/MainLayoutsAdmin.vue';

export default {
    props: ["licenca", "modulos", "modulos_licenca"],
    components: { MainLayoutsAdmin },
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        sessions() {
            return this.$page.props.sessions.empresa_sessao;
        },
    },
    mounted() {
        $('#table_licencas').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    data() {
        return {
            estados: [
                { 'id': "activo", 'text': "Activo" },
                { 'id': "desactivo", 'text': "Desactivo" },
            ],

            form: {
                titulo: this.licenca.titulo ?? "",
                modulo_id: this.licenca.modulo_id ?? "",
                licenca_id: this.licenca.id ?? "",
            },
        };
    },
    mounted() { },
    methods: {
        submit() {
            this.$Progress.start();

            axios
                .post(`/licenca-modulo`, this.form)
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
                        title: "Este Modulo já esta cadastrado para esta licenca!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                    });

                    console.error("Erro ao fazer requisição POST:", error);
                });
        },
        deleteItem(item) {
            
            axios.get(`/licenca-modulo/${item.id}`)
            .then((response) => {
              this.$Progress.finish();
              Swal.fire({
                toast: true,
                icon: "success",
                title: "Modulo Eliminado com Sucesso!",
                animation: false,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000
              })
    
              window.location.reload();
            })
            .catch((error) => {
    
              this.$Progress.fail();
              Swal.fire({
                toast: true,
                icon: "danger",
                title: "Ocorreu um erro ao tantar eliminar o modulo!",
                animation: false,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000
              })
    
            });
        
        },
    },
};
</script>
