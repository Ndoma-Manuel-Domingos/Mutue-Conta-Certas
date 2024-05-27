<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">LISTAGEM DE DIÁRIOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/diarios">Dashboard</a></li>
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
                  <a href="/diarios/create" class="btn btn-sm btn-info mx-1">
                    <i class="fas fa-plus"></i> CRIAR DIÁRIO</a
                  >
                  <button class="btn btn-sm float-right btn-danger mx-1">
                    <i class="fas fa-file-pd"></i> Imprimir
                  </button>
                  <a href="" class="btn btn-sm mx-1 btn-success float-right" @click="ExportarExcelDiario()">
                  <i class="fas fa-file-excel"></i> Exportar</a
                >
                </h3>

              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Código</th>
                      <th>Designação</th>
                      <th>Estado</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in diarios" :key="item">
                      <td>#</td>
                      <td>{{ item.numero }}</td>
                      <td>{{ item.designacao }}</td>
                      <td class="text-capitalize">{{ item.estado }}</td>
                      <td>
                        <div class="float-right">
                          <a
                            :href="`/diarios/${item.id}/edit`"
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
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>

<script>
import Paginacao from "../../components/Paginacao.vue";

export default {
  props: ["diarios"],
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
      input_busca_diarios: "",
      params: {},
    };
  },
  mounted() {
    $('#tabela_de_diarias').DataTable({
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

    input_busca_diarios: function (val) {
      this.params.input_busca_diarios = val;
      this.updateData();
    },

  },
  methods: {

    ExportarExcelDiario() {
      window.open(
        `exportar-diario-excel`
      );
    },

    updateData() {
      this.$Progress.start();
      this.$inertia.get("/diarios", this.params, {
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
        .get(`/diarios/${item.id}`)
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
