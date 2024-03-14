<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAM CLASSES</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/classes">Dashboard</a></li>
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
                <a href="/classes/create" class="btn btn-info"> <i class="fas fa-plus"></i> CRIAR CLASSES</a>

                <button class="btn btn-danger" @click="imprimirContas()">
                  <i class="fas fa-save"></i> Imprimir Contas
                </button>
              </div>
              <div class="card-body">
                <div class="table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in classes.data" :key="item">
                        <td>#</td>
                        <td>{{ item.classe.numero }}</td>
                        <td>{{ item.classe.designacao }}</td>
                        <td class="text-capitalize">{{ item.estado }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/classes/${item.id}/edit`" class="btn btn-sm btn-success"><i
                                class="fas fa-edit"></i> Editar</a>
                            <a @click="openModal(item.id)" data-toggle="modal" data-target="#exampleModalCenter"
                              class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i>
                              Desactivar</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                Total Registro: {{ classes.total }}</Link>
                <Paginacao :links="classes.links" :prev="classes.prev_page_url" :next="classes.next_page_url" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-exclamation-circle" style="font-size: 3em; color: #C70039;"></i> <br> Tem certeza que deseja desactivar essa classe ?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
            <button type="button" class="btn btn-primary" @click="deleteItem(id_delete)">Sim</button>
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
    'classes'
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
  },
  data() {
    return {
      dialog: false,
      id_delete: null,
    };
  },
  mounted() { },
  methods: {
    imprimirContas() {
      // window.open("/estudante/lista-avaliacao/" + btoa(btoa(btoa(this.usuario))) + '/' +  btoa(btoa(btoa(this.ano_lectivo))) + '/' + btoa(btoa(btoa(this.query.id_semestre))));
      window.open("imprimir-classes");
    },
    openModal(id) {
      this.dialog = true;
      this.id_delete = id;
    },
    closeModal() {
      this.dialog = false;
    },
    deleteItem(id) {
      
      this.$Progress.start();
      axios.delete('classes/delete/' + id).then((response) => {
        Swal.fire({
          toast: true,
          icon: "success",
          title: "O registo foi eliminado com sucesso!",
          animation: false,
          position: "top-end",
          showConfirmButton: false,
          timer: 4000
        })

        window.location.reload();
        console.log("Resposta da requisição POST:", response.data);
      }).catch((error) => {

        // sweetError("Ocorreu um erro ao actualizar Instituição!");
        this.$Progress.fail();
        Swal.fire({
          toast: true,
          icon: "danger",
          title: "Correu um erro ao eliminar os dados!",
          animation: false,
          position: "top-end",
          showConfirmButton: false,
          timer: 4000
        })

        console.error("Erro ao fazer requisição POST:", error);
      });

    },
  },
};
</script>

<style>
.modal-title {
  text-align: center;
  width: 100%;
}
</style>