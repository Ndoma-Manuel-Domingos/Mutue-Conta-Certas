<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">FLUXO DE CAIXA DIÁRIO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/fluxos-caixas">Fluxo de Caixa</a>
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
                    <span>Saldo Anterior:</span>
                    <span class="text-info">{{ formatarValorMonetario(saldo_antes_movimento) }}</span>
                    ||
                    <span>Saldo Actual: </span>
                    <span class="text-info"> {{ formatarValorMonetario(saldo_final) }}</span>

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
                        v-model="sub_conta_id"
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
                        type="text"
                        id="valor"
                        v-model="form.valor"
                        class="form-control"
                        placeholder="Informe o Valor"
                        @input="formatInput"
                        @keypress="validateInput"
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
                        @select="selecinarTipoMovimento($event)"
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

                    <div class="col-12 col-md-3 mb-4" >
                      <label for="centro_custo" class="form-label"
                        >Centro Custo</label
                      >
                      <Select2
                        v-model="form.centro_custo"
                        id="centro_custo"
                        class="col-12 col-md-12"
                        :options="centro_custo"
                        :settings="{ width: '100%' }"
                      />
                      <span></span>
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="referencia_documento" class="form-label">Referência do Documento</label>
                      <input type="text" id="referencia_documento" v-model="form.referencia_documento" placeholder="EX: MUT2024/458383" class="form-control" >
                      <span class="text-danger" v-if="form.errors && form.errors.referencia_documento">{{ form.errors.referencia_documento }}</span>
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="data_lancamento" class="form-label"
                        >Data Lançamento</label
                      >
                      <input
                        id="data_lancamento"
                        v-model="form.data_lancamento"
                        class="form-control"
                        placeholder="data_lancamento"
                        type="date"
                        />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.data_lancamento"
                        >{{ form.errors.data_lancamento }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="exercicio_id" class="form-label"
                        >Exercício</label
                      >
                      <Select2
                        id="exercicio_id"
                        v-model="exercicio_id"
                        :options="exercicios"
                        :settings="{ width: '100%' }"
                      />
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="periodo_id" class="form-label"
                        >Período</label
                      >
                      <Select2
                        id="periodo_id"
                        v-model="periodo_id"
                        :options="periodos"
                        :settings="{ width: '100%' }"
                      />
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="requisitante" class="form-label"
                        >Requisitante</label
                      >
                      <input
                        id="requisitante"
                        v-model="form.requisitante"
                        class="form-control"
                        placeholder="Requisitante"
                        />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.requisitante"
                        >{{ form.errors.requisitante }}</span
                      >
                    </div>

                  </div>
                </div>
                <div class="card-footer">
                  <button  type="submit" class="btn btn-success btn-sm ml-2">
                    <i class="fas fa-save"></i> SALVAR</button
                  >
                </div>
              </div>
            </form>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                <h6>Listagem de Movimento</h6>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-sm" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center" rowspan="2">Nº</th>
                      <th class="text-center" rowspan="2" style="width: 100px;">Nº Mov</th>
                      <th rowspan="2" style="width: 450px;">Descrição</th>
                      <th rowspan="2" class="text-right" style="width: 250px">Débito</th>
                      <th rowspan="2" class="text-right" style="width: 250px">Crédito</th>
                      <th class="text-center" colspan="2" style="width: 250px">Saldo</th>
                      <th rowspan="2" style="width: 150px;">Acções</th>
                    </tr>
                    <tr>
                      <th class="text-right">Antes do Movimento</th>
                      <th class="text-right">Após Movimento</th>
                    </tr>
                  </thead>

                  <tbody>

                    <tr>
                      <td colspan="3" class="text-right">Total</td>
                      <td class="text-right text-primary">{{ resultados.total_debito == null ? 0 : formatarValorMonetario(resultados.total_debito) }}</td>
                      <td class="text-right text-danger">{{ resultados.total_credito == null ? 0 : formatarValorMonetario(resultados.total_credito) }}</td>
                      <td class="text-right text-primary">{{ formatarValorMonetario(saldo_antes_movimento) }}</td>
                      <td class="text-right text-primary">{{ formatarValorMonetario(saldo_final) }}</td>
                      <td></td>
                    </tr>

                    <tr v-for="(item, index) in item_movimentos" :key="index">
                      <td class="text-center">{{ index + 1 }} </td>
                      <td class="text-center">{{ item.movimento.lancamento_atual ?? '' }}</td>
                      <td class="text-left">{{ item.descricao }} </td>
                      <td class="text-right text-primary">{{ formatarValorMonetario(item.debito) }} </td>
                      <td class="text-right text-danger">{{ formatarValorMonetario(item.credito) }} </td>

                      <td class="text-right text-primary" v-if="item.debito > item.credito">{{ formatarValorMonetario(item.debito - item.credito) }}</td>
                      <td class="text-right text-danger" v-if="item.credito > item.debito">{{ formatarValorMonetario(item.credito - item.debito) }}</td>

                      <td class="text-right text-primary" v-if="item.debito > item.credito">{{ formatarValorMonetario(item.debito - item.credito) }}</td>
                      <td class="text-right text-danger" v-if="item.credito > item.debito">{{ formatarValorMonetario(item.credito - item.debito) }}</td>

                      <td style="width: 150px;">
                        <a @click="remover_fluxo_caixa_item(item)" class="text-danger btn btn-default mx-1"><i class="fas fa-times"></i></a>
                        <a @click="editar_fluxo_caixa_item(item)" class="text-success btn btn-default mx-1"><i class="fas fa-edit"></i></a>
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
    "centro_custo",
    "tipo_proveitos",
    "contrapartidas",
    "saldo",
    "item_movimentos", "resultados",
    "taxas",
    "saldo_final",
    "saldo_antes_movimento",
    "exercicios",
    "periodos"
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
      }
      if(this.saldo.credito > this.saldo.debito){
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

      form: this.$inertia.form({
        sub_conta_id: this.sub_conta_id,
        valor: 0,
        designacao: "",
        documento_id: "",
        tipo_credito_id: this.tipo_credito_id,
        contrapartida_id: this.contrapartida_id,
        tipo_movimento_id: "",
        tipo_proveito_id: "",
        taxa_iva_id: "1",
        requisitante: "",
        data_lancamento: "",
        referencia_documento: "",
        centro_custo: "",
        exercicio_id: "",
        periodo_id: "",
      }),

      contrapartida_id: "",
      tipo_credito_id: "",
      sub_conta_id: "",

      valorIntroduzido: 0,

      params: {},

    };
  },
  mounted() {
    // this.form.valor = this.formatValor(this.form.valor);
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
    tipo_credito_id: function (val) {
      this.params.tipo_credito_id = val;
      this.updateData();
    },
    sub_conta_id: function (val) {
      this.params.sub_conta_id = val;
      this.updateData();
    },
  },

  methods: {
    formatInput() {
      // Implemente aqui a lógica de formatação desejada
      // Por exemplo, para formatar como moeda
      // this.form.valor = this.form.valor.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');

      this.form.valor = (parseFloat(this.form.valor.replace(/\D/g, '')) / 100).toFixed(2)
      .replace('.', ',')
      .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    removeFormatting() {

      // Remover a formatação
      // this.form.valor = this.form.valor.replace(/\D/g, '');

      this.form.valor = parseFloat(this.form.valor.replace(/\./g, '').replace(',', '.'));

    },

    selecinarTipoMovimento({id, text}){

      this.removeFormatting();

      if(this.form.tipo_movimento_id == 1){

        if( this.form.valor > this.saldo_final ){
          Swal.fire({
            toast: true,
            icon: "error",
            title: "Operação Invalida, saldo insuficiente para este operação!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 10000,
          });

          this.form.tipo_movimento_id = "";
          this.formatInput()
        }
      }else {
        this.formatInput()
      }

    },

    updateData() {
      this.$Progress.start();
      this.$inertia.get("/fluxos-caixas/create", this.params, {
        preserveState: true,
        preverseScroll: true,
        onSuccess: () => {
          this.$Progress.finish();
        },
      });
    },

    validateInput(event) {
      // Permitir apenas números
      const keyCode = event.keyCode;
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 8 && keyCode !== 9 && keyCode !== 37 && keyCode !== 39) {
        event.preventDefault();
      }
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
        console.log(item)
    },

    submit() {

      this.$Progress.start();

      this.form.tipo_credito_id = this.tipo_credito_id;
      this.form.contrapartida_id = this.contrapartida_id;
      this.form.sub_conta_id = this.sub_conta_id;

      // console.log(this.form.valor)

      // console.error(this.removeFormatting());

      // return

      if(this.form.tipo_movimento_id == 1){
        if( this.form.valor > this.saldo_final ){
          Swal.fire({
            toast: true,
            icon: "error",
            title: "Operação Invalida, saldo insuficiente para este operação!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 10000,
          });

          this.form.tipo_movimento_id = "";
          this.form.valor = 0;
        }
      }

      this.form.post(route("fluxos-caixas.store"), {
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

          // if(this.form.tipo_movimento_id == 1){
            this.imprimirComprovativo();
          // }

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
      // Converte o valor para uma string com duas casas decimais
      let valorFormatado = Number(valor).toFixed(2);

      // Separa a parte inteira da parte decimal
      let partes = valorFormatado.split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes[1] ? "," + partes[1] : "";

      // Adiciona separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

      // Retorna o valor formatado
      return parteInteira + parteDecimal;
    },

    /*formatarValorMonetario(valor) {
      // Converter o número para uma string e separar parte inteira da parte decimal
      let partes = String(valor).split(".");
      let parteInteira = partes[0];
      let parteDecimal = partes.length > 1 ? "." + partes[1] : "";

      // Adicionar separadores de milhar
      parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

      // Retornar o valor formatado
      return parteInteira + parteDecimal;
    },*/

    imprimirComprovativo() {
      window.open(
        `/fluxos-caixas-imprimir-nota-entregue?id=0`,
        "_blank"
      );
    },

    imprimirPlano() {
      window.open("imprimir-movimentos");
    },
  },
};
</script>


