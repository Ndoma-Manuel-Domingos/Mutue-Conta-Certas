<template>
    <MainLayouts>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">CRIAR NOVO CENTRO DE CUSTO</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/centro_de_custo">Listagem</a></li>
                            <li class="breadcrumb-item active">Criar Centro de Custo</li>
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
                                        <div class="col-12 col-md-4 mb-4">
                                            <label for="numero" class="form-label">Designação do Centro de Custo</label>
                                            <input type="text" id="numero" v-model="form.designacao" placeholder="Informe a designação" class="form-control">
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
                
                
                <div class="row">
                  <div class="col-12 col-md-12">
                    <div class="card">
                      <div class="card-header">
                    
                      </div>
                      <div class="card-body">
                        <div class="table-responsive p-0">
                          <table class="table table-bordered table-hover" id="tabela_dos_tipos_de_moedas">
                            <thead>
                              <tr>
                                <th>Id</th>
                                <th>Designação</th>
                                <th class="text-right">Ações</th>
                              </tr>
                            </thead>
        
                            <tbody>
                              <tr v-for="item in centro_de_custo" :key="item">
                                <td>{{ item.id }}</td>
                                <td>{{ item.designacao }}</td>
                                <td>
                                    <div class="text-right">
                                        <a
                                          :href="`/centro_de_custo/${item.id}/edit`"
                                          class="btn btn-sm btn-success"
                                          ><i class="fas fa-edit"></i> Editar</a
                                        >
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
        </div>
    </MainLayouts>
</template>

<script>
export default {
    props: ["centro_de_custo"],
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
            form: {
                designacao: "",
            },
        };
    },
    mounted() { },
    methods: {
        submit() {
            this.$Progress.start();
            axios.post(route('centro_de_custo.store'), this.form)
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
                        title: "Ocorreu um erro ao salvar os dados!",
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