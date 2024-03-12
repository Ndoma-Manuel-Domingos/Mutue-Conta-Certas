<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Permissões do Utilizador</h1>
          </div>

          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <!-- <button
                  class="btn btn-info float-right"
                  type="button"
                  data-toggle="modal"
                  data-target="#modelPermissions"
                >
                  <i class="fas fa-plus"></i>
                  Adicionar Permissões
                </button> -->
                <button
                  class="btn btn-info float-right"
                  type="button"
                  @click="adicionar_permission(user)"
                >
                  <i class="fas fa-plus"></i>
                  Gestão Permissões
                </button>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Permissões</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td> 
                          <div class="d-flex flex-wrap">
                            <template v-for="permission in permissions_user" :key="permission">
                              <span class="badge badge-info mx-1 my-1">{{ permission.name ?? 'sem Perfil' }} |</span>
                            </template>
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

        <!-- MODAL REGISTAR NOVO CAIXA  -->
        <div class="modal fade" id="modalCaixas">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ formTitle }}</h4>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="submit">
                <div class="modal-body py-3">
                  <div class="row">
                    <div class="col-12 col-md-12 mb-3">
                      <div class="form-group">
                        <label for="" class="form-label"
                          >Designação do Perfil</label
                        >
                        <input
                          type="text"
                          v-model="form.name"
                          class="form-control"
                          placeholder="Ex: Operadpr"
                        />
                      </div>
                      <span class="text-danger d-block">{{
                        form.errors.name
                      }}</span>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                      <div class="form-group">
                        <label for="" class="form-label">Permissões</label>
                        <select
                          v-model="form.permissions"
                          class="form-control text-uppercase"
                          multiple
                        >
                          <option value="todos">Todas as permissões</option>
                          <option
                            :value="item.id"
                            v-for="item in permissions"
                            :key="item"
                          >
                            {{ item.name }}
                          </option>
                        </select>
                      </div>
                      <span class="text-danger d-block">{{
                        form.errors.name
                      }}</span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button
                    type="button"
                    class="btn btn-black"
                    data-dismiss="modal"
                  >
                    Fechar
                  </button>
                  <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        
        <div class="modal fade" id="modelPermissions">
          <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">
                  Adicionar Permissões ao(a) {{ user.nome }}
                </h4>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
                
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="actualizar_permissoes"
                >
                  Actualizar Permissões
                </button>
                
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Nº</th>
                        <th>Permissão</th>
                        <th>Data criação</th>
                        <th>Data Actualização</th>
                        <th width="50px" class="text-right">Acção</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template
                        v-for="(item, index) in listagem_permissions"
                        :key="item.id"
                      >
                        <tr>
                          <td>{{ ++index }}</td>
                          <td>{{ item.name ?? "" }}</td>
                          <td>{{ item.created_at ?? "" }}</td>
                          <td>{{ item.updated_at ?? "" }}</td>
                          <td class="text-right">
                            <input
                              type="checkbox"
                              v-model="form_permissions.permissions_id"
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
                  @click="actualizar_permissoes"
                >
                  Actualizar Permissões
                </button>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import Paginacao from "../../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from "../../../components/Alert";

export default {
  props: ["permissions_user", "listagem_permissions", "user"],
  components: { Paginacao },
  data() {
    return {
      itemId: null,
      form: this.$inertia.form({
        name: "",
        permissions: [],
      }),
      
      title: "",
      form_permissions: this.$inertia.form({
        user_id: this.user.pk_utilizador,
        permissions_id: [],
      }),

      role: {},
      role_permissions: [],
      isUpdate: false,
    };
  },
  computed: {
    formTitle() {
      return this.isUpdate ? "Editar Perfil" : "Adicionar Perfil";
    },
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
    search: function (val) {
      this.params.search = val;
      this.updateData();
    },
  },

  methods: {
    adicionar_permission(user) 
    {
        
      this.form_permissions.permissions_id = [];
      this.permissions_user.forEach(permission => {
        this.form_permissions.permissions_id.push(permission.id);
      });    
    
      $("#modelPermissions").modal("show");   

    },
    
    actualizar_permissoes() {
      axios.post('/roles/adicionar-permissions-utilizador', this.form_permissions)
      .then(response => {
        this.form_permissions.reset();
        this.$Progress.finish();
        sweetSuccess("Dados salvos com sucesso");
        $("#modalCaixas").modal("hide");
        $("#modelPermissions").modal("hide");
        window.location.reload()
        console.log('Resposta da requisição POST:', response.data);
      })
      .catch(error => {
        sweetError("Ocorreu um erro ao Cadastrar Perfil!");
        this.$Progress.fail();
        console.error('Erro ao fazer requisição POST:', error);
      });
    },


    submit() {
      this.$Progress.start();
      if (this.isUpdate) {
        axios
          .put(`/roles/${this.itemId}`, this.form)
          .then((response) => {
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalInstituicaoRenuncia").modal("hide");
            this.$Progress.finish();
            window.location.reload();
            console.log("Resposta da requisição PUT:", response.data);
          })
          .catch((error) => {
            console.log(errors);
            sweetError("Ocorreu um erro ao actualizar Instituição!");
            console.error("Erro ao fazer requisição PUT:", error);
            this.$Progress.fail();
          });
      } else {
        axios
          .post("/roles", this.form)
          .then((response) => {
            this.form.reset();
            this.$Progress.finish();
            sweetSuccess("Dados salvos com sucesso");
            $("#modalInstituicaoRenuncia").modal("hide");
            window.location.reload();
            console.log("Resposta da requisição POST:", response.data);
          })
          .catch((error) => {
            this.$Progress.fail();
            sweetError("Ocorreu um erro ao actualizar Instituição!");
            console.error("Erro ao fazer requisição POST:", error);
          });
      }
    },
  },
};
</script>
