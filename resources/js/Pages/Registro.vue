<template>
    <body class="hold-transition login-page" >
        <AuthLayouts>

            <!-- <video autoplay muted loop id="myVideo">
              <source src="images/pexels-rodnae-productions-7821854 (1080p).mp4" type="video/mp4">
            </video> -->

            <div class="login-box">        
                <div class="card card-outline">
                    <div class=" text-center mb-1 pt-5">
                        <img src="images/logotipo_contas_centas.png"  alt="MUTUE CONTAS CERTAS" class="elevation-0" style="opacity: 0.8;width: 200px;height: 100px;"/>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <h6 class="text-center text-danger pb-3" v-if="form.errors.acesso">Acesso Registro</h6>

                            <div class="col-12 mb-3">
                                <label for="" class="form-label">Nome</label>
                                <div class="input-group">
                                    <input type="text" v-model="form.name" :class="{'is-invalid' : form.errors.name}" class="form-control form-control-sm " placeholder="Informe o seu Nome" />
                                </div>
                                <span v-if="form.errors.name" class="login-box-msg text-danger" >{{ form.errors.name }}</span>
                            </div>
                                                        
                            <!-- <div class="col-12 mb-3">
                                <label for="" class="form-label">Tipo Empresa</label>
                                <div class="input-group">
                                    <Select2
                                        v-model="form.tipo_empresa"
                                        id="estado"
                                        class="col-12 col-md-12"
                                        :options="tipo_clientes"
                                        :settings="{ width: '100%' }"
                                        @select="getTipoCliente($event)"
                                    />
                                </div>
                                <span v-if="form.errors.tipo_empresa" class="login-box-msg text-danger" >{{ form.errors.tipo_empresa }}</span>
                            </div> -->
                            
                            <div class="col-12 mb-3">
                                <label for="" class="form-label">NIF</label>
                                <div class="input-group">
                                    
                                    <input type="text" v-model="form.nif" :class="{'is-invalid' : form.errors.nif}" class="form-control form-control-sm " placeholder="Número de Identificação Fiscal" />
                                </div>
                                <span v-if="form.errors.nif" class="login-box-msg text-danger" >{{ form.errors.nif }}</span>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="" class="form-label">E-mail</label>
                                <div class="input-group">
                                    
                                    <input type="text" v-model="form.email" :class="{'is-invalid' : form.errors.email}" class="form-control form-control-sm " placeholder="Email" />
                                </div>
                                <span v-if="form.errors.email" class="login-box-msg text-danger" >{{ form.errors.email }}</span>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="" class="form-label">Senha</label>
                                <div class="input-group">
                                    
                                    <input type="password" v-model="form.password" :class="{'is-invalid' : form.errors.password}"  class="form-control form-control-sm " placeholder="Senha" />
                                </div>
                                <span v-if="form.errors.password" class="login-box-msg text-danger" >{{ form.errors.password }}</span>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="" class="form-label">Confirmar Senha</label>
                                <div class="input-group">
                                    <input type="password" v-model="form.r_password" :class="{'is-invalid' : form.errors.r_password}"  class="form-control form-control-sm " placeholder="Confirmar Senha" />
                                </div>
                                <span v-if="form.errors.r_password" class="login-box-msg text-danger" >{{ form.errors.r_password }}</span>
                            </div>
                            
                            

                            <div class="col-12">
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-lg btn-info btn-block">
                                            Entrar
                                        </button>
                                    </div>
                                    <div class="col-12 text-center my-2">
                                        <a href="/login">Login</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthLayouts>
    </body>
</template>

<script>
    import AuthLayouts from "./Layouts/AuthLayouts.vue";
    export default {
        layout: AuthLayouts,
        
        data() {
            return {
                tipo_clientes: [
                    { id: "singular", text: "Singular" },
                    { id: "juridica", text: "Jurídica" },
                ],
                
                form: this.$inertia.form({
                    email: "",
                    name: "",
                    // tipo_empresa: "",
                    nif: "",
                    password: "",
                    r_password: "",
                })
            }
        },
        
        methods: {
            getTipoCliente() {
                if(this.form.tipo_empresa == "singular"){
                    alert("Empresa Singular")
                }
                if(this.form.tipo_empresa == "juridica"){
                    alert("Empresa Juridica")
                }
            },
            
            submit(){
                this.$Progress.start();
                
                this.form.post(route("mc.register"), {
                    preverseScroll: true,
                    onSuccess: () => {
                      this.form.reset();
                      this.$Progress.finish();
            
                      Swal.fire({
                        title: "Bom Trabalho",
                        text: "Conta Criada com sucesso",
                        icon: "success",
                        confirmButtonColor: "#3d5476",
                        confirmButtonText: "Ok",
                        onClose: () => {},
                      });
                      
                      location.reload();
                    },
                    onError: (errors) => {
                      console.log(errors);
                      this.$Progress.fail();
                    },
                });
         
            }
        }
    };
    
</script>

<style>
/* Style the video: 100% width and height to cover the entire window */
    #myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
    }
</style>

