<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">DETALHES DE IMOBILIZADO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/imobilizados">Voltar</a></li>
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
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <tbody>
                      <tr>
                          <th style="width: 250px">Exercício:</th>
                          <th style="width: 250px">Período:</th>
                          <th style="width: 250px">Estado:</th>
                      </tr>
                      <tr>
                          <td style="">{{ imobilizado.exercicio.designacao }}</td>
                          <td style="">{{ imobilizado.periodo.designacao }}</td>
                          <td style="">{{ imobilizado.status }}</td>
                      </tr>
                      
                      <tr>
                          <th style="width: 250px">Código:</th>
                          <th style="width: 250px">Descrição:</th>
                          <th style="width: 250px">Classificação:</th>
                      </tr>
                      <tr>
                          <td style="">{{ imobilizado.sigla }}</td>
                          <td style="">{{ imobilizado.designacao }}</td>
                          <td style="">{{ imobilizado.classificacao.designacao }}</td>
                      </tr>
                  </tbody>
              </table>
      
              <table class="table table-bordered table-hover">
                  <tbody>
                      <tr>
                          <th style="width: 250px">Data de Aquisição:</th>
                          <th style="width: 250px">Data de Utilização:</th>
                          <th style="width: 250px" class="text-right">Valor de Aquisição:</th>
                      </tr>
                      <tr>
                          <td style="">{{ imobilizado.data_aquisicao }}</td>
                          <td style="">{{ imobilizado.data_utilizacao }}</td>
                          <td style="" class="text-right">{{ formatarValorMonetario(imobilizado.valor_aquisicao, 2, '.', ',')  }}</td>
                      </tr>
                      <tr>
                          <th style="">Quantidade:</th>
                          <th style="">Categoria:</th>
                          <th style="">Amortizações:</th>
                      </tr>
                      <tr>
                          <td style="">{{ imobilizado.quantidade }}</td>
                          <td style="">{{ imobilizado.amortizacao.designacao }}</td>
                          <td style="">{{ imobilizado.amortizacao_item.designacao }}</td>
                      </tr>
                      <tr>
                          <th style="">Vida Útil Anos:</th>
                          <th style="">Taxa de Amortização anual:</th>
                          <th style=""></th>
                      </tr>
                      <tr>
                          <td style="">{{ imobilizado.amortizacao_item.vida_util }}</td>
                          <td style="">{{ imobilizado.amortizacao_item.taxa }}</td>
                          <td style=""></td>
                      </tr>
                  </tbody>
              </table>
        
              <table class="table table-bordered table-hover">
                  <tbody>
                      <tr>
                          <th style="width: 250px" class="text-right">Valor Actual:</th>
                          <th style="width: 250px" class="text-right">Valor Anterior:</th>
                          <th style="width: 250px"></th>
                      </tr>
                      <tr>
                          <td style="" class="text-right">{{ formatarValorMonetario(imobilizado.valor_aquisicao, 2, '.', ',')  }}</td>
                          <td style="" class="text-right">{{ formatarValorMonetario(imobilizado.valor_aquisicao, 2, '.', ',')  }}</td>
                          <td style=""></td>
                      </tr>
      
                  </tbody>
              </table>
              
              </div>
              <div class="card-footer">
                <a @click="imprimirComprovativo(imobilizado.id)" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</a>
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
  props: ["imobilizado"],
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
    // $('#tabela_de_diarias').DataTable({
    //   "responsive": true, "lengthChange": true, "autoWidth": true,
    // });
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
        
    imprimirComprovativo(id) {
      window.open(
        `/imprimir-ficha-imobilizado?id=${id}`,
        "_blank"
      );
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