<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">PLANO GERAL DE CONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/dashboard">Dashboard</a>
              </li>
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
                <a
                  href="/classes/create"
                  class="btn-sm btn-primary btn float-left mx-1"
                  ><i class="fas fa-plus"></i> Criar Classe</a
                >
                <a
                  href="/contas/create"
                  class="btn-sm btn-primary btn float-left mx-1"
                  ><i class="fas fa-plus"></i> Criar Conta</a
                >
                <a
                  href="/sub-contas/create"
                  class="btn-sm btn-primary btn float-left mx-1"
                  ><i class="fas fa-plus"></i> Criar Subconta</a
                >
                <a
                  @click="imprimirPlano()"
                  class="btn-sm btn-danger btn float-left mx-1"
                  ><i class="fas fa-file-pdf"></i> Visualizar PGC</a
                >
              </div>

              <div class="card-body">
                <table
                  class="table table-bordered table-hover"
                  id="tabela_de_balancetes"
                >
                  <thead>
                    <tr class="btn-dark">
                      <th class="text-uppercase">PLANO GERAL DE CONTAS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="plan in plano" :key="plan">
                      <tr>
                        <th class="text-uppercase">
                          CLASSE - {{ plan.classe.numero }} -
                          {{ plan.classe.designacao }}
                        </th>
                      </tr>
                      <template
                        v-for="conta in plan.classe.contas_empresa"
                        :key="conta"
                      >
                        <tr class="btn-light">
                          <th style="padding-left: 60px">
                            {{ conta.conta.numero }} -
                            {{ conta.conta.designacao }}
                          </th>
                        </tr>

                        <tr
                          v-for="sub_conta in conta.sub_contas_empresa"
                          :key="sub_conta"
                        >
                          <td style="padding-left: 120px">
                            {{ sub_conta.numero }} - {{ sub_conta.designacao }}
                          </td>
                        </tr>
                      </template>
                    </template>
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
  props: ["plano"],
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
      classe_designacao: "",
      conta_designacao: "",
      subconta_designacao: "",
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

    classe_designacao: function (val) {
      this.params.classe_designacao = val;
      this.updateData();
    },

    conta_designacao: function (val) {
      this.params.conta_designacao = val;
      this.updateData();
    },

    subconta_designacao: function (val) {
      this.params.subconta_designacao = val;
      this.updateData();
    },
  },
  mounted() {
    this.exercicio_id = this.sessions_exercicio
      ? this.sessions_exercicio.id
      : "";

    $("#tabela_de_balancetes").DataTable({
      responsive: true,
      lengthChange: true,
      autoWidth: true,
    });
  },
  methods: {
    updateData() {
      this.$Progress.start();

      this.$inertia.get("/plano-geral-contas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    deleteItem(item) {
      console.log(item.id);
    },
    imprimirPlano() {
      window.open("imprimir-plano");
    },
    mudar_estado(item) {
      this.$Progress.start();

      axios
        .get(`/classes/${item.id}`)
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