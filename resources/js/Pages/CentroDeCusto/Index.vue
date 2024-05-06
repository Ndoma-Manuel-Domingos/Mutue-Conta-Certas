<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">LISTAGEM DE MOEDAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/moeda">Dashboard</a></li>
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
                <a href="/centro_de_custo/create" class="btn btn-info btn-sm mx-1">
                  <i class="fas fa-plus"></i> CRIAR CENTRO DE CUSTO</a
                >

                <button
                  class="btn btn-danger btn-sm mx-1"
                  @click="imprimirPeriodos()"
                >
                  <i class="fas fa-file-pdf"></i> Visualizar
                </button>

                <div class="card-tools">
                  <div class="input-group input-group" style="width: 450px">
                    <input
                      type="text"
                      v-model="input_busca_moedas"
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
                  <table class="table table-bordered table-hover" id="tabela_dos_tipos_de_moedas">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Id</th>
                        <th>Designação</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in centro_de_custo" :key="item">
                        <td>#</td>
                        <td>{{ item.id }}</td>
                        <td>{{ item.designacao }}</td>
                        <td>
                          <div class="float-right">
                            <a
                              :href="`/centro_de_custo/${item.id}/edit`"
                              class="btn btn-sm btn-success"
                              ><i class="fas fa-edit"></i> Editar</a
                            >
                            <a
                              @click="deleteItem(item)"
                              class="btn btn-sm btn-danger mx-1"
                              ><i class="fas fa-trash"></i> Apagar</a
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
import Paginacao from "../../components/Paginacao.vue";

export default {
  props: ["centro_de_custo"],
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
  },
  data() {
    return {
      input_busca_moedas: "",
      params: {},
    };
  },
  mounted() {
    $('#tabela_dos_tipos_de_moedas').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
    });
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

    input_busca_moedas: function (val) {
      this.params.input_busca_moedas = val;
      this.updateData();
    },
  },
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/centro_de_custo", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    imprimirPeriodos() {
      window.open("imprimir-periodos");
    },
  },
};
</script>