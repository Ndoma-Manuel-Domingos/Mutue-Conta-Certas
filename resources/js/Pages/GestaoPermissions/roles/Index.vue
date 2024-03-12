<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Perfil</h1>
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
                <button
                  class="btn btn-info float-right"
                  type="button"
                  data-toggle="modal"
                  data-target="#modalCaixas"
                >
                  <i class="fas fa-plus"></i>
                  Novos Perfil
                </button>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Nº</th>
                        <th>Perfil</th>
                        <!-- <th>Data criação</th> -->
                        <!-- <th>Data Actualização</th> -->
                        <th>Permissões</th>
                        <th width="50px">Adicionar Permissões</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in items.data" :key="item.id">
                        <td>#{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <!-- <td>{{ item.created_at }}</td> -->
                        <!-- <td>{{ item.updated_at }}</td> -->
                        <td> 
                          <div class="d-flex flex-wrap">
                            <template v-for="permission in item.permissions" :key="permission">
                              <span class="badge badge-info mx-1 my-1">{{ permission.name ?? 'sem Perfil' }} |</span>
                            </template>
                          </div>
                        </td>
                        <td>
                          <a
                            class="btn-sm btn-info mx-1"
                            @click="adicionar_permission(item)"
                          >
                            <i class="fas fa-plus"></i>
                            Adicionar
                          </a>
                          
                          <a
                            class="btn-sm btn-success mx-1"
                            @click="editar_perfil(item)"
                          >
                            <i class="fas fa-edit"></i>
                            Editar
                          </a>
                          
                          <a
                            class="btn-sm btn-danger mx-1"
                            @click="remover_perfil(item)"
                          >
                            <i class="fas fa-trash"></i>
                            Excluir
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ items.total }}</Link
                >
                <Paginacao
                  :links="items.links"
                  :prev="items.prev_page_url"
                  :next="items.next_page_url"
                />
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
                          :disabled="disabled_status"
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
                  Adicionar Permissões ao perfil de: {{ title }}
                </h4>
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
                
                <button
                  type="button"
                  class="btn btn-primary mb-2 float-right"
                  @click="actualizar_permissoes"
                >
                  Actualizar Permissões
                </button>
              
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
                        v-for="(item, index) in permissions"
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
  props: ["items", "permissions"],
  components: { Paginacao },
  data() {
    return {
      itemId: null,
      
      disabled_status: false,
      
      form: this.$inertia.form({
        name: "",
        permissions: [],
      }),
      
      title: "",
      form_permissions: this.$inertia.form({
        role_id: "",
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

  mounted() {},

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
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/roles.index", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },


    adicionar_permission(item) {
    
      this.title = item.name;
      this.form_permissions.role_id = item.id;
    
      this.$Progress.start();
      axios
        .get(`/roles/permissions/${item.id}`, {
          params: {},
        })
        .then((response) => {
          
          this.form_permissions.permissions_id = [];
          response.data.role.permissions.forEach(permission => {
            this.form_permissions.permissions_id.push(permission.id);
          });
          
          this.$Progress.finish();
        
        })
        .catch((error) => {
          this.$Progress.fail();
        });

      $("#modelPermissions").modal("show");
    },
    
    editar_perfil(item) {
      
      this.itemId = item.id;
      this.isUpdate = true;
      this.form.name = item.name;
      this.disabled_status = true,
 
      $("#modalCaixas").modal("show");
    },
    
    remover_perfil(item) {

    this.$Progress.start();
    axios
      .get(`/roles/excluir/${item.id}`)
      .then((response) => {
        sweetSuccess(response.data.message);
        this.$Progress.finish();
        window.location.reload()
      })
      .catch((error) => {
        this.$Progress.fail();
      });

  },
    
    actualizar_permissoes() {
      axios.post('/roles/adicionar-permissions', this.form_permissions)
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
        axios.put(`/roles/${this.itemId}/editar`, this.form)
          .then((response) => {
            
            this.isUpdate = false;
            this.itemId = null;
            this.form.reset();
            sweetSuccess(response.data.message);
            $("#modalCaixas").modal("hide");
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
            $("#modalCaixas").modal("hide");
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
