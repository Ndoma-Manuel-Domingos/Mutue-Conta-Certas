<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">LISTAGEM  DE CONTA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/contas">Dashboard</a></li>
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
                <a href="/contas/create" class="btn btn-info btn-sm mx-1"> <i class="fas fa-plus"></i> CRIAR CONTAS</a>

                <button class="btn btn-danger btn-sm mx-1" @click="imprimirContas()">
                  <i class="fas fa-save"></i> Imprimir Contas
                </button>
                
                <div class="card-tools">
                  <div class="input-group input-group" style="width: 450px">
                    <input
                      type="text"
                      v-model="input_busca_contas"
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
                        <th @click="order_by_codigo" style="cursor: pointer;">Código</th>
                        <th @click="order_by_conta" style="cursor: pointer;">Conta</th>
                        <th @click="order_by_classe" style="cursor: pointer;">Classe</th>
                        <th>Estado</th>
                        <th class="text-right">Ações</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr v-for="item in contas.data" :key="item">
                        <td>#</td>
                        <td>{{ item.conta.numero }}</td>
                        <td>{{ item.conta.designacao }}</td>
                        <td>{{ item.classe.designacao }}</td>
                       
                        <td class="text-capitalize">{{ item.estado }}</td>
                        <td>
                          <div class="float-right">
                            <a :href="`/contas/${item.id}/edit`" class="btn btn-sm btn-success"><i class="fas fa-edit"></i> Editar</a>
                            
                            <a @click="mudar_estado(item)" class="btn btn-sm btn-info mx-1" v-if="item.estado == 'desactivo'"><i class="fas fa-check"></i> Activar</a>
                            <a @click="mudar_estado(item)" class="btn btn-sm btn-danger mx-1" v-else><i class="fas fa-times"></i> Desctivar</a>
                            
                            <!-- <a href="" class="btn btn-sm btn-danger mx-1"><i class="fas fa-trash"></i> Apagar</a> -->
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                Total Registro: {{ contas.total }}</Link>
                <Paginacao :links="contas.links" :prev="contas.prev_page_url" :next="contas.next_page_url" />
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
    'contas'
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
    return {
      input_busca_contas: "",
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
    
    input_busca_contas: function (val) {
      this.params.input_busca_contas = val;
      this.updateData();
    },

  },
  mounted() {},
  methods: {
      
    order_by_codigo(){
      this.params.order_by = "numero";
      this.updateData();
    },    
    
    order_by_conta(){
      this.params.order_by = "conta";
      this.updateData();
    }, 
    
    order_by_classe(){
      this.params.order_by = "classe";
      this.updateData();
    },  
  
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/contas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
  
  
    imprimirContas() {
      // window.open("/estudante/lista-avaliacao/" + btoa(btoa(btoa(this.usuario))) + '/' +  btoa(btoa(btoa(this.ano_lectivo))) + '/' + btoa(btoa(btoa(this.query.id_semestre))));
      window.open("imprimir-contas");
    },
    mudar_estado(item) {
      this.$Progress.start();

      axios.get(`/contas/${item.id}`)
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