<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">CRIAR MOVIMENTOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/movimentos">Listagem</a></li>
              <li class="breadcrumb-item active">Criar Movimentos</li>
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
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header"></div>
            
                            <div class="card-body">
                              <div class="row">
                              
                                <div class="col-12 col-md-3 mb-4">
                                  <label for="exercicio_id" class="form-label">Exercícios</label>
                                    <Select2 v-model="form.exercicio_id" id="exercicio_id"
                                      :options="exercicios" :settings="{ width: '100%' }" 
                                    />
                                  <span class="text-danger" v-if="form.errors && form.errors.exercicio_id">{{ form.errors.exercicio_id }}</span>
                                </div>
                                
                                <div class="col-12 col-md-3 mb-4">
                                  <label for="data_lancamento" class="form-label">Data</label>
                                  <input type="date" id="data_lancamento" v-model="form.data_lancamento" class="form-control" >
                                  <span class="text-danger" v-if="form.errors && form.errors.data_lancamento">{{ form.errors.data_lancamento }}</span>
                                </div>
                                
                                <div class="col-12 col-md-6 mb-4">
                                  <label for="lancamento_atual" class="form-label">Lançamento Actual</label>
                                  <input type="number" id="lancamento_atual" v-model="form.lancamento_atual" class="form-control" >
                                  <span class="text-danger" v-if="form.errors && form.errors.lancamento_atual">{{ form.errors.lancamento_atual }}</span>
                                </div>
                                
                                <div class="col-12 col-md-6 mb-4">
                                  <label for="diario_id" class="form-label">Diários</label>
                                  <Select2 v-model="form.diario_id" id="diario_id"
                                    :options="diarios" :settings="{ width: '100%' }" 
                                    @select="getDiario($event)"
                                  />
                                  <span class="text-danger" v-if="form.errors && form.errors.diario_id">{{ form.errors.diario_id }}</span>
                                </div>
                                
                                <div class="col-12 col-md-6 mb-4">
                                  <label for="tipo_documento_id" class="form-label">Tipo Documento</label>
                                    <Select2 v-model="form.tipo_documento_id" id="tipo_documento_id"
                                      :options="tipo_documentos" :settings="{ width: '100%' }" 
                                    />
                                  <span class="text-danger" v-if="form.errors && form.errors.tipo_documento_id">{{ form.errors.tipo_documento_id }}</span>
                                </div>
                                
                                <div class="col-12 col-md-12 mb-4">
                                  <label for="sub_conta_id" class="form-label">Contas</label>
                                    <Select2 v-model="form.sub_conta_id" id="sub_conta_id" 
                                      :options="contas" :settings="{ width: '100%' }" 
                                      @select="addSubContaMovimento($event)"
                                    />
                                  <span class="text-danger" v-if="form.errors && form.errors.sub_conta_id">{{ form.errors.sub_conta_id }}</span>
                                </div>
                              
                              </div>
                            </div>
            
                            <div class="card-footer">
                              <button class="btn btn-success">
                                <i class="fas fa-save"></i> Salvar
                              </button>
                            </div>
                        </div>
                      </div>
                      <div class="col-12 col-md-6">
                        <div class="card">
                          <div class="card-header"></div>
                          <div class="card-body">
                            <table class="table" style="width: 100%;">
                              <thead>
                                <tr>
                                  <th>Conta</th>
                                  <th>Debito</th>
                                  <th>Crédito</th>
                                  <th>IVA</th>
                                  <th>Descrição</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="item in item_movimentos" :key="item">
                                  <td width="200px">{{ item.subconta.numero }} - {{ item.subconta.designacao }}</td>
                                  <td><input type="number" class="form-control" style="border: none" v-model="item.debito" @keydown.enter="input_valor_debito(item)"></td>
                                  <td><input type="number" class="form-control" style="border: none" v-model="item.credito" @keydown.enter="input_valor_credito(item)"></td>
                                  <td><input type="text" class="form-control" style="border: none"  value="0"></td>
                                  <td><input type="text" class="form-control" style="border: none" value="0" ></td>
                                  <td width="2px"><a @click="remover_item_movimento(item)" class="text-danger"><i class="fas fa-times"></i></a></td>
                                </tr>
                              </tbody>
                              
                              <tfoot>
                                <tr>
                                  <th>Total</th>
                                  <th>{{ formatValor(resultados.total_debito ?? 0) }}</th>
                                  <th>{{ formatValor(resultados.total_credito ?? 0) }}</th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </MainLayouts>
</template>
    
<script>
export default {
  props: [
    "diarios",
    "exercicios",
    "tipo_documentos",
    "contas",
    "item_movimentos",
    "ultimo_movimento",
    "resultados",
  ],
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
      
      estados: [
        {'id': "activo", 'text': "Activo"},
        {'id': "desactivo", 'text': "Desactivo"},
      ],
      
      debito: 0,
      credito: 0,
      
      tipo_documentos: [],
      item_movimentos: [],
      resultados: [],
      
      form: {
        exercicio_id: "",
        data_lancamento: "",
        lancamento_atual: this.ultimo_movimento + 1,
        diario_id: "",
        tipo_documento_id: "",
      },
    };
  },
  mounted() {},
  methods: {

    getDiario({ id, text }) {
      axios
        .get(`/get-diario/${this.form.diario_id}`)
        .then((response) => {
          
          this.form.numero = response.data.diario.numero + ".";
          this.tipo_documentos = [];
          this.tipo_documentos = response.data.tipos_documentos;
          
        })
        .catch((error) => {});
    },

    addSubContaMovimento({ id, text }) {
      axios
        .get(`/adicionar-conta-movimento/${this.form.sub_conta_id}`)
        .then((response) => {
          
          this.item_movimentos = [];
          this.item_movimentos = response.data.item_movimentos;
          this.resultados = response.data.resultados;
          
        })
        .catch((error) => {});
    },
    
    remover_item_movimento(item) {
      axios
        .get(`/remover-conta-movimento/${item.id}`)
        .then((response) => {
          
          this.item_movimentos = [];
          this.item_movimentos = response.data.item_movimentos;
          this.resultados = response.data.resultados;
          
        })
        .catch((error) => {});
     console.log(item)
    },    

    input_valor_debito(item) {
      axios
        .get(`/alterar-debito-conta-movimento/${item.id}/${item.debito}`)
        .then((response) => {
          
          this.item_movimentos = [];
          this.item_movimentos = response.data.item_movimentos;
          this.resultados = response.data.resultados;
          
        })
        .catch((error) => {});
        
        event.preventDefault();
    },
    
    input_valor_credito(item) {
      axios
        .get(`/alterar-credito-conta-movimento/${item.id}/${item.credito}`)
        .then((response) => {
          
          this.item_movimentos = [];
          this.item_movimentos = response.data.item_movimentos;
          this.resultados = response.data.resultados;
          
        })
        .catch((error) => {});
        
        event.preventDefault();
    },
    
    // input_valor_iva(item) {
    //   axios
    //     .get(`/alterar-iva-conta-movimento/${item.id}/${item.iva}`)
    //     .then((response) => {
          
    //       this.item_movimentos = [];
    //       this.item_movimentos = response.data.item_movimentos;
    //       this.resultados = response.data.resultados;
          
    //     })
    //     .catch((error) => {});
        
    //     event.preventDefault();
    // },
    
    
    // input_valor_descricao(item) {
    //   axios
    //     .get(`/alterar-descricao-conta-movimento/${item.id}/${item.descricao}`)
    //     .then((response) => {
          
    //       this.item_movimentos = [];
    //       this.item_movimentos = response.data.item_movimentos;
    //       this.resultados = response.data.resultados;
          
    //     })
    //     .catch((error) => {});
        
    //     event.preventDefault();
    // },

        
    submit() {
      this.$Progress.start();
      
      if(this.resultados.total_debito != this.resultados.total_credito){
        
        Swal.fire({
          toast: true,
          icon: "error",
          title: "infelizmente não podemos concluir este lançamento, Porque o valor do Débito é diferente do Crédito!",
          animation: false,
          position: "top-end",
          showConfirmButton: false,
          timer: 4000
        })
        
        this.$Progress.fail();
        return;
      }
      
      alert("passa");
      return ;

      

      axios.post(route('movimentos.store'), this.form)
        .then((response) => {
          // this.form.reset();
          this.$Progress.finish();
          
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Dados Salvos com Sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
   
          window.location.reload();
          console.log("Resposta da requisição POST:", response.data);
        })
        .catch((error) => {
          
          // sweetError("Ocorreu um erro ao actualizar Instituição!");
          this.$Progress.fail();
          Swal.fire({
            toast: true,
            icon: "danger",
            title: "Correu um erro ao salvar os dados!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000
          })
          
          console.error("Erro ao fazer requisição POST:", error);
        });
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
    