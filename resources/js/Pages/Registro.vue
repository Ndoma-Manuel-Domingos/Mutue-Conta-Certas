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
    };
</script>

<script setup>
    import { useForm } from "@inertiajs/inertia-vue3";
    import { getCurrentInstance } from 'vue'

    const form = useForm({
        email: "",
        name: "",
        nif: "",
        password: "",
        r_password: "",
    })

    const internalInstance = getCurrentInstance();

    const submit = () => {
        form.post(route("mc.register"), {
            onBefore: () => {
                internalInstance.appContext.config.globalProperties.$Progress.start();
            },
            onSuccess: () => {
                internalInstance.appContext.config.globalProperties.$Progress.finish();
                location.reload();
            },
            onError: () => {
                internalInstance.appContext.config.globalProperties.$Progress.fail();
            }
        })
    }
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

