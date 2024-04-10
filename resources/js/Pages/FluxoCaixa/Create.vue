<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">FLUXO DO CAIXA</h1>
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

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header"> 
                <a href="#" class="btn btn-info btn-sm"> <i class="fas fa-plus"></i> LANÇAR MOVIMENTOS</a>
              </div>
              <div class="card-body">
               
              </div>
          
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
  
<script>

export default {

  props: [
    'fluxos_caixas'
  ],
  components:{ },
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
      params: {},
    };
  },
  mounted() {
    $('#tabela_de_movimentos').DataTable({
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
  },
  
  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get("/fluxos-caixas", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    imprimirPlano() {
      window.open("imprimir-movimentos");
    },
    
    formatarValorMonetario(valor) {
        // Converter o número para uma string e separar parte inteira da parte decimal
        let partes = String(valor).split('.');
        let parteInteira = partes[0];
        let parteDecimal = partes.length > 1 ? '.' + partes[1] : '';
    
        // Adicionar separadores de milhar
        parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
        // Retornar o valor formatado
        return parteInteira + parteDecimal;
    },
    
    formatValor(atual) {
      const valorFormatado = Intl.NumberFormat("pt-br", {
        style: "currency",
        currency: "AOA",
      }).format(atual);
      return valorFormatado;
    },
  },
};
</script>
  
  