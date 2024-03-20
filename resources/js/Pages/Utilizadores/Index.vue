<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM DE UTILIZADORES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/utilizadores">Dashboard</a></li>
              <li class="breadcrumb-item active">Listagem</li>
            </ol>
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
                <h3 class="card-title">
                  <a href="/utilizadores/create" class="btn btn-info">
                    <i class="fas fa-plus"></i> CRIAR UTILIZADOR</a
                  >
                </h3>
                <div class="card-tools">
                  <div class="input-group input-group" style="width: 450px">
                    <input
                      type="text"
                      v-model="input_busca_utilizadores"
                      class="form-control float-right"
                      placeholder="Informe a campo"
                    />
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Admin</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in utilizadores.data" :key="item">
                        <td>#</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.telefone ?? '000-000-000' }}</td>
                        <td>{{ item.is_admin == 1 ? 'SIM' : 'NÃO' }}</td>
                        <td>
                          <div class="float-right">
                            <a
                              :href="`/utilizadores/${item.id}/edit`"
                              class="btn btn-sm btn-success"
                              ><i class="fas fa-edit"></i> Editar</a
                            >
                            <a
                              @click="mudar_estado(item)"
                              class="btn btn-sm btn-info mx-1"
                              v-if="item.estado == 'desactivo'"
                              ><i class="fas fa-check"></i> Activar</a
                            >
                            <a
                              @click="mudar_estado(item)"
                              class="btn btn-sm btn-danger mx-1"
                              v-else
                              ><i class="fas fa-times"></i> Desctivar</a
                            >
                          </div>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
  
<script>
import Paginacao from "../../components/Paginacao.vue";

export default {
  props: ["utilizadores"],
  components: {
    Paginacao,
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    sessions() {
      return this.$page.props.sessions.empresa_sessao;
    },
    sessions_exercicio() {
      return this.$page.props.sessions.exercicio_sessao;
    },
  },
  data() {
    return {
      input_busca_utilizadores: "",
      params: {},
    };
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
    
    input_busca_utilizadores: function (val) {
      this.params.input_busca_utilizadores = val;
      this.updateData();
    },

  },
  methods: {
    
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/utilizadores", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    mudar_estado(item) {
      this.$Progress.start();

      axios
        .get(`/utilizadores/${item.id}`)
        .then((response) => {
          this.$Progress.finish();
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Estado Alterado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          window.location.reload();
        })
        .catch((error) => {
          this.$Progress.fail();
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Correu um erro ao Estado Alterado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });
        });
    },
    
  },
};
</script>
  
  