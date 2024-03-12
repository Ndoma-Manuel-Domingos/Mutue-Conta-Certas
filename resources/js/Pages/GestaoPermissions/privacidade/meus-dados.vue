<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0">Actualizar Dodos</h1>
          </div>
          <div class="col-sm-4">
            <button
              class="btn btn-dark float-right mr-1"
              type="button"
              @click="voltarPaginaAnterior"
            >
              <i class="fas fa-arrow-left"></i> VOLTAR A PÁGINA ANTERIOR
            </button>
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
                  <!-- <h5> Actualizar meus dados </h5> -->
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Nome completo</label>
                        <input
                          type="text"
                          v-model="form.nome"
                          class="form-control"
                          placeholder="Informe o nome"
                        />
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Genero</label>
                        
                        <Select2 v-model="form.genero" 
                          id="genero" class="col-12 col-md-12"
                          :options="sexos" :settings="{ width: '100%' }" 
                        />

                      </div>
                    </div>
                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Estado Civil</label>
                        
                        <Select2 v-model="form.estado_civil" 
                          id="estado_civil" class="col-12 col-md-12"
                          :options="estado_civil" :settings="{ width: '100%' }" 
                        />

                      </div>
                    </div>
                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Data Nascimento</label>
                        <input
                          type="date"
                          v-model="form.data_nascimento"
                          class="form-control"
                          placeholder="Informe "
                        />
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>E-mail</label>
                        <input
                          type="email"
                          v-model="form.email"
                          class="form-control"
                          placeholder="Informe o email"
                        />
                      </div>
                    </div>
                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Tipo Documentos</label>
                        
                        <Select2 v-model="form.tipo_documento" 
                          id="tipo_documento" class="col-12 col-md-12"
                          :options="tipos_documentos" :settings="{ width: '100%' }" 
                        />

                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Nº Documento</label>
                        <input
                          type="text"
                          v-model="form.bilheite"
                          class="form-control"
                          placeholder="Informe número do bilheite"
                        />
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Data Emissão</label>
                        <input
                          type="date"
                          v-model="form.data_emissao"
                          class="form-control"
                          placeholder="Informe"
                        />
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Data Expiração</label>
                        <input
                          type="date"
                          v-model="form.data_expiracao"
                          class="form-control"
                          placeholder="Informe "
                        />
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Telefone</label>
                        <input
                          type="text"
                          v-model="form.telefone"
                          class="form-control"
                          placeholder="Informe número de telefone"
                        />
                      </div>
                    </div>
                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Telefone 2</label>
                        <input
                          type="text"
                          v-model="form.telefone2"
                          class="form-control"
                          placeholder="Informe número de telefone2"
                        />
                      </div>
                    </div>

                  </div>
                </div>
                
                <div class="card-footer">
                    <button class="btn btn-info">Actualizar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>


<script>
import { Link } from "@inertiajs/inertia-vue3";
import { sweetSuccess, sweetError } from "../../../components/Alert";
import Paginacao from "../../../Shared/Paginacao";

export default {
  props: ["user", "dados", "sexos", "estado_civil", "tipos_documentos"],
  components: { Link, Paginacao },
  data() {
    return {
      form: this.$inertia.form({
        nome: this.dados ? this.dados.nome_completo : "",
        genero: this.dados ? (this.dados.genero ? this.dados.genero.Codigo : "") : "",
        estado_civil: this.dados ? (this.dados.estado_civil ? this.dados.estado_civil.Codigo : "") : "",
        tipo_documento: this.dados ? (this.dados.tipos_documentos ? this.dados.tipos_documentos.Codigo : "") : "",
        email: this.dados ? this.dados.email : "",
        bilheite: this.dados ? this.dados.num_doc_identificacao : "",
        data_emissao: this.dados ? this.dados.data_de_emissao_documento : "",
        data_expiracao: this.dados ? this.dados.data_de_expiracao_documento : "",
        data_nascimento: this.dados ? this.dados.data_de_nascimento : "",
        telefone: this.dados ? this.dados.telefone1 : "",
        telefone2: this.dados ? this.dados.telefone2 : "",
        codigo: this.dados ? this.dados.pk_pessoa : "",
      }),
    };
  },


  methods: {

    submit() {
      axios
        .post("/privacidade/meus-dados", this.form)
        .then((response) => {
          if (response.data.status == "404") {
            sweetError(response.data.error);
          } else {
            this.form.reset();
            this.$Progress.finish();
            sweetSuccess(response.data.message);
            $("#modal-xl").modal("hide");
            window.location.reload();
          }
        })
        .catch((error) => {
          sweetError("Ocorreu um erro ao Cadastrar Perfil!");
        });
    },

    // actualizar_perfil() {
    //   Swal.fire({
    //     title: "Atenção!",
    //     text: "Têm certeza que desaja adicionar este perfil ao utilizador?",
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Sim, Adicionar!",
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       axios
    //         .post("/roles/utilizadores-roles", this.form_perfil)
    //         .then((response) => {
    //           this.form_perfil.reset();
    //           this.$Progress.finish();
    //           sweetSuccess("Dados salvos com sucesso");
    //           $("#modelActualizarPerfil").modal("hide");
    //           window.location.reload();
    //           console.log("Resposta da requisição POST:", response.data);
    //         })
    //         .catch((error) => {
    //           sweetError("Ocorreu um erro ao Cadastrar Perfil!");
    //           console.error("Erro ao fazer requisição POST:", error);
    //         });
    //     }
    //   });
    // },

    voltarPaginaAnterior() {
      window.history.back();
    },
  },
};
</script>
      
      
      