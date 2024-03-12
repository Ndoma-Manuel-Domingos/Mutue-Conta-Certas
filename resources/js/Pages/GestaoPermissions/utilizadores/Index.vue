<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0">Utilizadores</h1>
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
            <div class="card">
              <div class="card-header">
                <h5>
                  Utilizadores com Perfil
                  <span class="float-right"
                    ><a
                      href=""
                      class="btn btn-primary"
                      type="button"
                      data-toggle="modal"
                      data-target="#modal-xl"
                      >NOVO UTILIZADOR</a
                    ></span
                  >
                </h5>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Nº</th>
                        <!-- <th>Codigo Importado</th> -->
                        <th>Código</th>
                        <th>Nome</th>
                        <th width="150px">Perfil</th>
                        <th width="50px">Acção</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <!-- <td></td> -->
                        <td></td>
                        <td>
                          <input
                            type="text"
                            v-model="nome_text_input"
                            class="form-control"
                            placeholder="Informe o Nome"
                          />
                        </td>
                      </tr>
                      <tr
                        v-for="(item, index) in utilizadores.data"
                        :key="item"
                      >
                        <td>#{{ ++index }}</td>
                        <!-- <td>{{ item.codigo_importado }}</td> -->
                        <td>{{ item.pk_utilizador }}</td>
                        <td>{{ item.nome ?? "" }}</td>
                        <td>
                          <template v-for="role in item.roles" :key="role">
                            <span>{{ role.name ?? "sem Perfil" }} |</span>
                          </template>
                        </td>
                        <td>
                          <a
                            class="btn-sm btn-dark mx-1"
                            :href='`/permissions-utilizadores/${item.pk_utilizador}`'
                          >
                            <i class="fas fa-redo-alt"></i>
                            Atruibuir Permissões
                          </a>
                          
                          <a
                            class="btn-sm btn-info mx-1"
                            @click="adicionar_perfil(item)"
                          >
                            <i class="fas fa-redo-alt"></i>
                            Actualizar Perfil
                          </a>
                          
                          <a
                            class="btn-sm btn-danger mx-1"
                            @click="receentarSenha(item)"
                          >
                            <i class="fas fa-lock"></i>
                            Redefinir Senha
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ utilizadores.total }}</Link
                >
                <Paginacao
                  :links="utilizadores.links"
                  :prev="utilizadores.prev_page_url"
                  :next="utilizadores.next_page_url"
                />
                <!-- <p><strong>Obs: Sabendo que um utilizador por ter vários perfil, para remover um perfil em especifico do utilizador clica sobre o mesmo perfil!</strong></p> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modelActualizarPerfil">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Actualizar Perfil do(a): {{ title }}</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!--  -->

            <div class="table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Perfil</th>
                    <th width="50px" class="text-right">Acção</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="item in roles" :key="item.id">
                    <tr>
                      <td>{{ item.text ?? "" }}</td>
                      <td class="text-right">
                        <input
                          type="checkbox"
                          v-model="form_perfil.role_id"
                          style="width: 20px; height: 20px"
                          :value="item.id"
                        />
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Fechar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="actualizar_perfil"
            >
              Actualizar Perfil
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-xl">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Dados do utitlizador</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nome</label>
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
                    <label>Gênero</label>
                    
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
                    <label>Nº Bilhete</label>
                    <input
                      type="text"
                      v-model="form.bilheite"
                      class="form-control"
                      placeholder="Informe número do bilhete"
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
                    <label>Senha</label>
                    <input
                      type="password"
                      v-model="form.senha"
                      @input="validarSenha"
                      class="form-control"
                      placeholder="Informe a senha"
                    />
                    <p :class="{ 'text-success': senhaValida, 'text-danger': !senhaValida }">{{ mensagem }}</p>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Confirmar Senha</label>
                    <input 
                      type="password"
                      v-model="form.confirmar_senha" 
                      @input="validarConfirmarSenha"
                      class="form-control"
                      placeholder="Informe a confirmação da senha"
                    />
                    <p :class="{ 'text-success': confirmar_senhaValida, 'text-danger': !confirmar_senhaValida }">{{ confirmar_mensagem }}</p>
                  </div>
                </div>
                
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Utilizador Sistema</label>
                    <Select2 v-model="form.user_pertence" 
                      id="user_pertence" class="col-12 col-md-12"
                      :options="items" :settings="{ width: '100%' }" 
                    />
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Perfil</label>
                    
                    <Select2 v-model="form.role_id" 
                      id="role_id" class="col-12 col-md-12"
                      :options="roles" :settings="{ width: '100%' }" 
                    />

                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Fechar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="criar_users_submit"
            >
              Criar novo usuário
            </button>
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
    props: ["utilizadores", "roles", "sexos", "estado_civil", "tipos_documentos"],
    components: { Link, Paginacao },
    data() {
      return {
        form: this.$inertia.form({
          nome: "",
          senha: "",
          confirmar_senha: "",
          estado_civil: "",
          email: "",
          role_id: "",
          tipo_documento: "",
          bilheite: "",
          genero: "", 
          telefone: "",
          user_pertence: "",
          
          mensagem: "",
          senhaValida: false,
          confirmar_mensagem: "",
          confirmar_senhaValida: false,
        }),
        
        form_perfil: this.$inertia.form({
          user_id: "",
          role_id: [],
        }),
        
        nome_text_input: "",
        title: "",
        
        items: [
          {id: "Finance", text: "Mutue Finança"},
          {id: "Cash", text: "Mutue Cash"},
          {id: "Finance-Cash", text: "Mutue Finance & Cash"},
        ],
  
        params: {},
      };
    },
  
    mounted() {
      this.params.data_inicio = this.data_inicio;
    },
  
    watch: {
      options: function (val) {
        this.params.page = val.page;
        this.params.page_size = val.itemsPerPage;
        if (val.sortBy.length != 0) {
          this.params.sort_by = val.sortBy[0];
          this.params.order_by = val.sortDesc[0] ? "desc" : "asc";
        } else {
          this.params.sort_by = null;
          this.params.order_by = null;
        }
        this.updateData();
      },
      operador: function (val) {
        this.params.operador = val;
        this.updateData();
      },
      nome_text_input: function (val) {
        this.params.nome_text_input = val;
        this.updateData();
      },
    },
  
    methods: {
      updateData() {
        this.$Progress.start();
        this.$inertia.get("/listagem-utilizadores", this.params, {
          preserveState: true,
          preverseScroll: true,
          onSuccess: () => {
            this.$Progress.finish();
          },
        });
      },
  
      validarSenha()
      {
        const regexSenha = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$!%*?^&])[A-Za-z\d@#$!%*?^&]{8,}$/;
      
        if (regexSenha.test(this.form.senha)) {
          this.senhaValida = true;
          this.mensagem = "Senha válida!";
        } else {
          this.senhaValida = false;
          this.mensagem = "Senha inválida. Certifique-se de ter pelo menos 8 caracteres, 1 dígito, 1 letra e 1 caractere especial.";
        }
      },
      
      validarConfirmarSenha()
      {
        const regexSenha = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$!%*?^&])[A-Za-z\d@#$!%*?^&]{8,}$/;
        
        if (regexSenha.test(this.form.confirmar_senha)) {
          this.confirmar_senhaValida = true;
          this.confirmar_mensagem = "Senha válida!";
        } else {
          this.confirmar_senhaValida = false;
          this.confirmar_mensagem = "Senha inválida. Certifique-se de ter pelo menos 8 caracteres, 1 dígito, 1 letra e 1 caractere especial.";
        }
      },
  
      adicionar_perfil(item) {
        this.title = item.nome ?? "";
        this.form_perfil.user_id = item.codigo_importado ?? "";
  
        this.$Progress.start();
        axios
          .get(`/roles/utilizador-perfil/${item.codigo_importado}`, {
            params: {},
          })
          .then((response) => {
            this.form_perfil.role_id = [];
            response.data.utilizador.roles.forEach((role) => {
              this.form_perfil.role_id.push(role.id);
            });
  
            this.$Progress.finish();
          })
          .catch((error) => {
            this.$Progress.fail();
          });
  
        $("#modelActualizarPerfil").modal("show");
      },
  
      editar(item) {
        
        // console.log(item)
      
        // this.title = item.nome ?? "";
        // this.form_perfil.user_id = item.codigo_importado ?? "";
  
        // this.$Progress.start();
        // axios
        //   .get(`/roles/utilizador-perfil/${item.codigo_importado}`, {
        //     params: {},
        //   })
        //   .then((response) => {
        //     this.form_perfil.role_id = [];
        //     response.data.utilizador.roles.forEach((role) => {
        //       this.form_perfil.role_id.push(role.id);
        //     });
  
        //     this.$Progress.finish();
        //   })
        //   .catch((error) => {
        //     this.$Progress.fail();
        //   });
  
        // $("#modelActualizarPerfil").modal("show");
      },  
      
      receentarSenha(item) {

        this.$Progress.start();
        axios
          .get(`/roles/utilizador-receentar-senha/${item.pk_utilizador}`, {
            params: {},
          })
          .then((response) => {
            sweetSuccess("Senha redefinada com sucesso!");
            this.$Progress.finish();
          })
          .catch((error) => {
            this.$Progress.fail();
          });
  
        // $("#modelActualizarPerfil").modal("show");
      },        
      remover_perfil(item) {
        Swal.fire({
          title: "Atenção!",
          text: "Têm certeza que desaja remover este perfil ao utilizador?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, Excluir!",
        }).then((result) => {
          if (result.isConfirmed) {
            this.$Progress.start();
            axios
              .get(`/roles/utilizador-remover-perfil/${item.codigo_importado}`, {
                params: {},
              })
              .then((response) => {
                Swal.fire("Exluido!", "Perfil excluido com successo", "success");
  
                window.location.reload();
                this.$Progress.finish();
              })
              .catch((error) => {
                this.$Progress.fail();
              });
          }
        });
      },
  
      criar_users_submit() {
        axios
          .post("/criar-utilizadores", this.form)
          .then((response) => {
            if(response.data.status == "404"){
             sweetError(response.data.error);
            }else{
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
  
      actualizar_perfil() {
        Swal.fire({
          title: "Atenção!",
          text: "Têm certeza que desaja adicionar este perfil ao utilizador?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, Adicionar!",
        }).then((result) => {
          if (result.isConfirmed) {
            axios
              .post("/roles/utilizadores-roles", this.form_perfil)
              .then((response) => {
                this.form_perfil.reset();
                this.$Progress.finish();
                sweetSuccess("Dados salvos com sucesso");
                $("#modelActualizarPerfil").modal("hide");
                window.location.reload();
                console.log("Resposta da requisição POST:", response.data);
              })
              .catch((error) => {
                sweetError("Ocorreu um erro ao Cadastrar Perfil!");
                console.error("Erro ao fazer requisição POST:", error);
              });
          }
        });
      },
  
      voltarPaginaAnterior() {
        window.history.back();
      },
    },
  };
</script>
      
      
      