<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">EDITAR FLUXO DE CAIXA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/fluxos-caixas">Editar Fluxo de Caixa</a>
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
            <form @submit.prevent="submit">
              <div class="card">
                <div class="card-header">
                  <h5>
                    ||
                    <span>Saldo Actual: </span>
                    <span class="text-info"> 0</span>

                    <span class="float-right"
                      >MOEDA: <strong class="text-info">{{ sessions ? (sessions.moeda ? (sessions.moeda.base ? sessions.moeda.base.sigla : "Sem moeda principal") : "Sem moeda principal") : "Sem moeda principal" }}</strong></span
                    >
                  </h5>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-3 mb-4">
                      <label for="sub_conta_id" class="form-label"
                        >Contas</label
                      >
                      <Select2
                        v-model="form.sub_conta_id"
                        id="sub_conta_id"
                        class="col-12 col-md-12"
                        :options="subcontas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.sub_conta_id"
                        >{{ form.errors.sub_conta_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="valor" class="form-label">Valor</label>
                      <input
                        type="number"
                        id="valor"
                        v-model="form.valor"
                        class="form-control"
                        placeholder="Informe o Valor"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.valor"
                        >{{ form.errors.valor }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="designacao" class="form-label"
                        >Descrição</label
                      >
                      <input
                        type="text"
                        id="designacao"
                        v-model="form.designacao"
                        class="form-control"
                        placeholder="Informe a designação"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.designacao"
                        >{{ form.errors.designacao }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="documento_id" class="form-label"
                        >Documentos</label
                      >
                      <Select2
                        v-model="form.documento_id"
                        id="documento_id"
                        class="col-12 col-md-12"
                        :options="documentos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.documento_id"
                        >{{ form.errors.documento_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="tipo_movimento_id" class="form-label"
                        >Tipo Movimento</label
                      >
                      <Select2
                        v-model="form.tipo_movimento_id"
                        id="tipo_movimento_id"
                        class="col-12 col-md-12"
                        :options="tipo_movimentos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.tipo_movimento_id"
                        >{{ form.errors.tipo_movimento_id }}</span
                      >
                    </div>

                    <div
                      class="col-12 col-md-3 mb-4"
                      v-show="form.tipo_movimento_id == 1"
                    >
                      <label for="tipo_credito_id" class="form-label"
                        >Tipo Crédito</label
                      >
                      <Select2
                        v-model="tipo_credito_id"
                        id="tipo_credito_id"
                        class="col-12 col-md-12"
                        :options="tipo_creditos"
                        :settings="{ width: '100%' }"
                      />
                      <span></span>
                    </div>

                    <div
                      class="col-12 col-md-3 mb-4"
                      v-show="form.tipo_movimento_id == 1"
                     >
                      <label for="contrapartida_id" class="form-label"
                        >Contrapartida</label
                      >
                      <Select2
                        v-model="contrapartida_id"
                        id="contrapartida_id"
                        class="col-12 col-md-12"
                        :options="contrapartidas"
                        :settings="{ width: '100%' }"
                      />
                      <span></span>
                    </div>

                    <div class="col-12 col-md-3 mb-4" v-show="form.tipo_movimento_id == 2">
                      <label for="tipo_proveito_id" class="form-label"
                        >Tipo Proveito</label
                      >
                      <Select2
                        v-model="form.tipo_proveito_id"
                        id="tipo_proveito_id"
                        class="col-12 col-md-12"
                        :options="tipo_proveitos"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.tipo_proveito_id"
                        >{{ form.errors.tipo_proveito_id }}</span
                      >
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <a @click="addSubContaMovimento()" class="btn btn-info btn-sm ml-2">
                    <i class="fas fa-plus"></i> {{ title_botao }}</a
                  >
                  <button  type="submit" class="btn btn-success btn-sm ml-2">
                    <i class="fas fa-plus"></i> FINALIZAR ACTUALIZAÇÕES</button
                  >
                </div>
              </div>
            </form>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
              </div>
              <div class="card-body">
                <table class="table table-bordered table-sm" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center">Nº</th>
                      <th class="text-center" style="width: 100px;">Nº Mov</th>
                      <th>Descrição</th>
                      <th class="text-right">Débito</th>
                      <th class="text-right">Crédito</th>
                      <th class="text-right">Saldo</th>
                      <th style="width: 150px;">Acções</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    <tr>
                      <td colspan="3" class="text-right">Total</td>
                      <td class="text-right text-primary">{{ movimento.debito == null ? 0 : formatarValorMonetario(movimento.debito) }}</td>
                      <td class="text-right text-danger">{{ movimento.credito == null ? 0 : formatarValorMonetario(movimento.credito) }}</td>
                      <td class="text-right text-primary" v-if="movimento.debito > movimento.credito">{{ movimento ? formatarValorMonetario(((movimento.debito + saldo_actual) - movimento.credito)) : "-"  }}</td>
                      <td class="text-right text-danger" v-if="movimento.credito > movimento.debito">{{ movimento ? formatarValorMonetario(((movimento.credito + saldo_actual) - movimento.debito)) : "-" }}</td>
                      <td></td>
                    </tr>
                    <tr v-for="(item, index) in item_movimentos" :key="index">
                      <td class="text-center">{{ index + 1 }} </td>
                      <td class="text-center">000</td>
                      <td class="text-left">{{ item.descricao }} </td>
                      <td class="text-right text-primary">{{ formatarValorMonetario(item.debito) }} </td>
                      <td class="text-right text-danger">{{ formatarValorMonetario(item.credito) }} </td>
                      
                      <td class="text-right text-primary" v-if="item.debito > item.credito">{{ formatarValorMonetario(item.debito - item.credito) }}</td>
                      <td class="text-right text-danger" v-if="item.credito > item.debito">{{ formatarValorMonetario(item.credito - item.debito) }}</td>
                      
                      <td style="width: 150px;">
                        <a @click="remover_fluxo_caixa_item(item)" class="text-danger btn btn-default mx-1"><i class="fas fa-times"></i></a>
                        <a @click.prevent="editar_fluxo_caixa_item(item)" class="text-success btn btn-default mx-1"><i class="fas fa-edit"></i></a>
                      </td>
                    </tr>
                  </tbody>
                
                </table>
              </div>
              <div class="card-footer"></div>
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
    "fluxos_caixas",
    "subcontas",
    "documentos",
    "tipo_movimentos",
    "tipo_creditos",
    "tipo_proveitos",
    "contrapartidas",
    "saldo",
    "item_movimentos", "resultados",
    "movimento"
  ],
  components: {},
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
    saldo_actual() {
      var saldo = 0;
      
      if (this.saldo.debito > this.saldo.credito) {
        saldo = this.saldo.debito - this.saldo.credito
      }else if(this.saldo.credito > this.saldo.debito){
        saldo = this.saldo.credito - this.saldo.debito
      }
      
      return saldo
    },
    
    title_botao() {
      return this.isUpdate
        ? "EDITAR"
        : "ADICIONAR";
    },
  },
  data() {
    return {
      
      itemId: null,
      isUpdate: false,
      tipo_credito_id: null,
      contrapartida_id: null,
      params: {},   
      
      item_movimentos: this.item_movimentos,
      resultados: this.resultados,
 
      
      form: this.$inertia.form({
        sub_conta_id: "",
        valor: "",
        designacao: "",
        documento_id: "",
        tipo_credito_id: this.tipo_credito_id,
        contrapartida_id: this.contrapartida_id,
        tipo_movimento_id: "",
        tipo_proveito_id: "",
        movimento_id: this.movimento.id,
      }),

    };
  },
  mounted() { },
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
    tipo_credito_id: function (val) {
      this.params.tipo_credito_id = val;
      this.updateData();
    },
  },

  methods: {
    updateData() {
      this.$Progress.start();
      this.$inertia.get(`/fluxos-caixas/${this.movimento.id}/edit`, this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },
    
    addSubContaMovimento() {
      
      this.form.tipo_credito_id = this.tipo_credito_id;
      this.form.contrapartida_id = this.contrapartida_id;
      
      if (this.isUpdate) {
 
        axios
          .put(`/editar-fluxo-caixa/${this.itemId}`, this.form)
          .then((response) => {
            
            this.item_movimentos = [];
            this.item_movimentos = response.data.item_movimentos;
            this.resultados = response.data.resultados;
              
            this.isUpdate = false; 
            this.itemId = null; 
            
          })
          .catch((error) => {});
        
      } else {
      
        axios
          .put(`/fluxos-caixas/${this.movimento_id}`, this.form)
          .then((response) => {
            
            this.item_movimentos = [];
            this.item_movimentos = response.data.item_movimentos;
            this.resultados = response.data.resultados;
            
          })
          .catch((error) => {});
      }
   
      window.location.reload();
    },
    
    
    editar_fluxo_caixa_item(item){

      this.form.valor = (item.tipo_movimento.sigla == "D" ? item.debito : item.credito);
      
      this.form.sub_conta_id = item.subconta_id;
      this.form.documento_id = item.documento_id;
      this.form.tipo_movimento_id = item.tipo_movimento_id;
      this.form.tipo_proveito_id = item.tipo_proveito_id;
      
      this.tipo_credito_id = item.tipo_credito_id;
      this.contrapartida_id = item.contrapartida_id;
      
      this.form.tipo_credito_id = item.tipo_credito_id;
      this.form.contrapartida_id = item.contrapartida_id;
      this.form.designacao = item.descricao;
      
      this.isUpdate = true;
      this.itemId = item.id;
      
    },
        
    remover_fluxo_caixa_item(item) {
      axios
        .get(`/remover-fluxo-caixa/${item.id}`)
        .then((response) => {
          
          this.item_movimentos = [];
          this.item_movimentos = response.data.item_movimentos;
          this.resultados = response.data.resultados;
          
        })
        .catch((error) => {});
   
    },    

    submit() {
      this.$Progress.start();
      this.form.put(route("fluxos-caixas.update", this.movimento_id), {
        preverseScroll: true,
        onSuccess: () => {
          this.form.reset();

          Swal.fire({
            toast: true,
            icon: "success",
            title: "Dados Salvos com Sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          window.location.reload();

          this.$Progress.finish();
        },
        onError: (errors) => {
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Impossível salvar o movimento!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });

          this.$Progress.fail();

          console.error("Erro ao fazer requisição POST:", errors);
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

    imprimirPlano() {
      window.open("imprimir-movimentos");
    },
  },
};
</script>
  
  