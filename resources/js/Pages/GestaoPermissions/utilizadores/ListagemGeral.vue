<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1 class="m-0 text-info">Utilizadores em geral</h1>
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
                </h5>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Nº</th>
                        <!-- <th>Codigo Importado</th> -->
                        <th>Codigo</th>
                        <th>Nome</th>
                        <th width="50px">Definir como utilizador do Mutue: </th>
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
                          <a
                            class="btn-sm btn-info mx-1"
                            @click="mudar_de_user_nos_sistema_financas(item)"
                          >
                            <i class="fas fa-user"></i>
                            Finanças
                          </a>
                          <a
                            class="btn-sm btn-info mx-1"
                            @click="mudar_de_user_nos_sistema_cash(item)"
                          >
                            <i class="fas fa-user"></i>
                            Cash
                          </a>
                          
                          <a
                            class="btn-sm btn-info mx-1"
                            @click="mudar_de_user_nos_sistema_financas_cash(item)"
                          >
                            <i class="fas fa-user"></i>
                            Finanças e Cash
                          </a>
                          
                          <a
                            class="btn-sm btn-danger mx-1"
                            @click="mudar_de_user_nos_sistema_nenhum(item)"
                          >
                            <i class="fas fa-user-times"></i>
                            Nenhum
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

  </MainLayouts>
</template>


<script>
  import { Link } from "@inertiajs/inertia-vue3";
  import { sweetSuccess, sweetError } from "../../../components/Alert";
  import Paginacao from "../../../Shared/Paginacao";
  
  export default {
    props: ["utilizadores"],
    components: { Link, Paginacao },
    data() {
      return {
        nome_text_input: "",
        params: {},
      };
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
      nome_text_input: function (val) {
        this.params.nome_text_input = val;
        this.updateData();
      },
    },
  
    methods: {
        updateData() {
            this.$Progress.start();
            this.$inertia.get("/listagem-utilizadores-geral", this.params, {
              preserveState: true,
              preverseScroll: true,
              onSuccess: () => {
                this.$Progress.finish();
              },
            });
        },
      
        mudar_de_user_nos_sistema_financas(item) {
            this.mudar_de_user_nos_sistema(item, "Finance");
        },
        
        mudar_de_user_nos_sistema_cash(item) {
            this.mudar_de_user_nos_sistema(item, "Cash");
        },
        
        mudar_de_user_nos_sistema_financas_cash(item) {
            this.mudar_de_user_nos_sistema(item, "Finance-Cash");
        },
        
        mudar_de_user_nos_sistema_nenhum(item) {
            this.mudar_de_user_nos_sistema(item, "Nenhum");
        },
        
  
        mudar_de_user_nos_sistema(item, sistema) {  
      
            this.$Progress.start();
            axios
              .get(`/mudar-de-user-nos-sistema/${item.pk_utilizador}/${sistema}`, {
                params: {},
            })
              .then((response) => {
                sweetSuccess("Acesso do utilizador actualizado com sucesso!");
               this.$Progress.finish();
            })
              .catch((error) => {
               this.$Progress.fail();
            });
        },
    
      voltarPaginaAnterior() {
        window.history.back();
      },
    },
  };
</script>
      
      
      