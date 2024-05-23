<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">LISTAGEM DE IMOBILIZADOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/imobilizados">Dashboard</a></li>
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
                  <a href="/imobilizados/create" class="btn btn-sm btn-info mx-1">
                    <i class="fas fa-plus"></i> CRIAR IMOBILIZADO</a
                  >
                  <button class="btn btn-sm float-right btn-danger mx-1">
                    <i class="fas fa-file-pd"></i> Imprimir
                  </button>
                </h3>

              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="tabela_de_diarias">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Codigo</th>
                      <th>Descrição</th>
                      <th class="text-right">Valor de Aquisição</th>
                      <th>Qtd</th>
                      <!-- <th>Data de Aquisição</th> -->
                      <!-- <th>Data de Utilização</th> -->
                      <th>Classificação</th>
                      <th>Taxa %</th>
                      <th>Vida útil Anos</th>
                      <th class="text-right">Valor Actual</th>
                      <th class="text-right">Ações</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="item in imobilizados" :key="item">
                      <td>#</td>
                      <td>{{ item.sigla }}</td>
                      <td>{{ item.designacao }}</td>
                      <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao) }}</td>
                      <td class="text-center">{{ item.quantidade }}</td>
                      <!-- <td>{{ item.data_aquisicao }}</td> -->
                      <!-- <td>{{ item.data_utilizacao }}</td> -->
                      <td>{{ item.classificacao.designacao }}</td>
                      <td class="text-center">{{ item.amortizacao_item.taxa }}</td>
                      <td class="text-center">{{ item.amortizacao_item.vida_util }}</td>
                      <td class="text-right">{{ formatarValorMonetario(item.valor_aquisicao * item.quantidade) }}</td>
                      <td>
                        <div class="float-right">
                          <a
                            :href="`/imobilizados/${item.id}`"
                            class="btn btn-sm btn-info"
                            ><i class="fas fa-info"></i> Datalhe</a
                          >
                          <a
                            :href="`/imobilizados/${item.id}/edit`"
                            class="btn btn-sm btn-primary mx-1"
                            ><i class="fas fa-edit"></i> Editar</a
                          >
                          <a
                            :href="`/imprimir-ficha-imobilizado?id=${item.id}`"
                            class="btn btn-sm btn-success mx-1" target="_blink"
                            ><i class="fas fa-print"></i> Imprimir</a
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
  props: ["imobilizados"],
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
    
    formatarValorMonetario(valor) {
      // Converter o número para uma string e separar parte inteira da parte decimal
      let partes = String(valor).split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes.length > 1 ? "." + partes[1] : "";

      // Adicionar separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

      // Retornar o valor formatado
      return parteInteira + parteDecimal;
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