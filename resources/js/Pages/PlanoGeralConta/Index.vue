<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">PLANO GERAL DE CONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Listagem</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <button class="btn btn-sm btn-danger"  @click="imprimirPlano()">
                <i class="fas fa-file pdf"></i> Imprimir PGC
              </button>
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
                <a href="/classes/create" class="btn-sm btn-primary btn float-left mx-1">Criar Classe</a>
                <a href="/contas/create" class="btn-sm btn-primary btn float-left mx-1">Criar Conta</a>
                <a href="/sub-contas/create" class="btn-sm btn-primary btn float-left mx-1">Criar Subconta</a>
                <Link href="" class="text-secondary">
                  Total Registro: {{ plano.total }}</Link
                >
                <Paginacao
                  :links="plano.links"
                  :prev="plano.prev_page_url"
                  :next="plano.next_page_url"
                />
              </div>
            
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-sm table-hover text-nowrap" v-for="plan in plano.data" :key="plan">
                    
                    <thead>
                      <tr class="btn-dark">
                        <th class="text-uppercase">{{ plan.classe.numero }} - {{ plan.classe.designacao }}</th>
                      </tr>
                    </thead>
                    <tbody v-for="conta in plan.classe.contas_empresa" :key="conta">
                      <tr class="btn-light">
                        <th style="padding-left: 60px;">{{ conta.conta.numero }} - {{ conta.conta.designacao }}</th>
                      </tr>

                      <tr v-for="sub_conta in conta.sub_contas_empresa" :key="sub_conta">
                        <td style="padding-left: 120px;">{{ sub_conta.numero }} - {{ sub_conta.designacao }} </td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <!-- <Link href="" class="text-secondary">
                  Total Registro: {{ plano.total }}</Link
                >
                <Paginacao
                  :links="plano.links"
                  :prev="plano.prev_page_url"
                  :next="plano.next_page_url"
                /> -->
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

  props: [
    'plano'
  ],
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
    return {};
  },
  mounted() { },
  methods: {

    deleteItem(item) {
      console.log(item.id)
    },
    imprimirPlano() {
      window.open("imprimir-plano");
    },
    mudar_estado(item) {
      this.$Progress.start();

      axios.get(`/classes/${item.id}`)
        .then((response) => {
          this.$Progress.finish();
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Estado Alterado com sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })

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
            timer: 4000
          })

        });
    },
  },
};
</script>