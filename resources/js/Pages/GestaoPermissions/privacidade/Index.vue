<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0">Privacidade</h1>
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
          <div class="col-12 col-md-7">
            <form @submit.prevent="submit">
              <div class="card">
                <div class="card-header">
                  <h5>Actualização de credências</h5>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-6 col-12 col-md-12">
                      <div class="form-group">
                        <label>Senha Actual <span class="text-danger">*</span></label>
                        <input
                          type="password"
                          v-model="form.senha_actual"
                          class="form-control"
                          placeholder="Informe a senha actual"
                        />
                      </div>
                    </div>

                    <div class="col-sm-6 col-12 col-md-12">
                      <div class="form-group">
                        <label>Nova Senha <span class="text-danger">*</span></label>
                        <input
                          type="password"
                          v-model="form.nova_senha"
                           @input="validarSenha"
                          class="form-control"
                          placeholder="Informe a senha nova"
                        />
                        <p :class="{ 'text-success': senhaValida, 'text-danger': !senhaValida }">{{ mensagem }}</p>
                      </div>
                    </div>

                    <div class="col-sm-12 col-12 col-md-12">
                      <div class="form-group">
                        <label>Confirmar Nome Senha <span class="text-danger">*</span></label>
                        <input
                          type="password"
                          v-model="form.confirmar_nova_senha"
                          @input="validarConfirmarSenha"
                          class="form-control"
                          placeholder="Informe a confirmação da nova senha"
                        />
                        <p :class="{ 'text-success': confirmar_senhaValida, 'text-danger': !confirmar_senhaValida }">{{ confirmar_mensagem }}</p>
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
  props: ["utilizadores", "roles"],
  components: { Link, Paginacao },
  data() {
    return {
      form: this.$inertia.form({
        senha_actual: "",
        nova_senha: "",
        confirmar_nova_senha: "",
      }),
      
      mensagem: "",
      senhaValida: false,
      confirmar_mensagem: "",
      confirmar_senhaValida: false,
    };
  },

  methods: {
  
    validarSenha(){
      
      const regexSenha = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$!%*?^&])[A-Za-z\d@#$!%*?^&]{8,}$/;
    
      if (regexSenha.test(this.form.nova_senha)) {
        this.senhaValida = true;
        this.mensagem = "Senha válida!";
      } else {
        this.senhaValida = false;
        this.mensagem = "Senha inválida. Certifique-se de ter pelo menos 8 caracteres, 1 dígito, 1 letra e 1 caractere especial.";
      }
    
      // console.log(this.form.senha)
    },
    
    validarConfirmarSenha()
    {
      const regexSenha = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$!%*?^&])[A-Za-z\d@#$!%*?^&]{8,}$/;
      
      if (regexSenha.test(this.form.confirmar_nova_senha)) {
        this.confirmar_senhaValida = true;
        this.confirmar_mensagem = "Senha válida!";
      } else {
        this.confirmar_senhaValida = false;
        this.confirmar_mensagem = "Senha inválida. Certifique-se de ter pelo menos 8 caracteres, 1 dígito, 1 letra e 1 caractere especial.";
      }

      // console.log(this.form.senha)
    },
    
  
    submit() {
        
        if(this.form.senha_actual == "" || this.form.nova_senha == "" || this.form.confirmar_nova_senha == ""){
            Swal.fire({
              title: "Atenção",
              text: "Todos os campos são obrigatórios",
              icon: "error",
              confirmButtonColor: "#3d5476",
              confirmButtonText: "Ok",
              onClose: () => {},
            });
        }else{
        
            axios
            .post("/privacidade/actualizar-credencias", this.form)
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
        
        }
        
        
        // if(this.form.nova_senha == ""){
        //     Swal.fire({
        //       title: "Atenção",
        //       text: "Informa a nova senha",
        //       icon: "error",
        //       confirmButtonColor: "#3d5476",
        //       confirmButtonText: "Ok",
        //       onClose: () => {},
        //     });
        // }
        
        // if(this.form.confirmar_nova_senha == ""){
        //     Swal.fire({
        //       title: "Atenção",
        //       text: "Informa a nova senha",
        //       icon: "error",
        //       confirmButtonColor: "#3d5476",
        //       confirmButtonText: "Ok",
        //       onClose: () => {},
        //     });
        // }
        
        console.log(this.form)
    
    //   axios
    //     .post("/criar-utilizadores", this.form)
    //     .then((response) => {
    //       if (response.data.status == "404") {
    //         sweetError(response.data.error);
    //       } else {
    //         this.form.reset();
    //         this.$Progress.finish();
    //         sweetSuccess(response.data.message);
    //         $("#modal-xl").modal("hide");
    //         window.location.reload();
    //       }
    //     })
    //     .catch((error) => {
    //       sweetError("Ocorreu um erro ao Cadastrar Perfil!");
    //     });
    },

    voltarPaginaAnterior() {
      window.history.back();
    },
  },
};
</script>
        
        
        