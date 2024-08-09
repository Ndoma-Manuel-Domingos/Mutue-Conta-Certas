<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">DETALHE DO FLUXO DE CAIXA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="/fluxos-caixas">Listagem</a>
              </li>
              <li class="breadcrumb-item active">Detalhe</li>
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
                <div class="row">
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Nº Lançamento:</strong> {{ movimento.lancamento_atual ?? "" }}</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Diário:</strong> {{ movimento.diario.designacao ?? "" }}</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Período:</strong> {{ movimento.periodo.designacao ?? "" }}</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Exercício:</strong> {{ movimento.exercicio.designacao ?? "" }}</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Data:</strong> {{ movimento.data_lancamento ?? "" }}</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <span class="lead"><strong>Operador:</strong> {{ movimento.criador.name ?? "" }}</span>
                    </div>
                </div>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Conta</th>
                      <th>Subconta</th>
                      <th>Tipo Movimento</th>
                      <!-- <th>Tipo Crédito</th> -->
                      <!-- <th>Tipo Proveito</th> -->
                      <!-- <th>Contrapartida</th> -->
                      <th>Documento</th>
                      <th class="text-right text-primary">Débito</th>
                      <th class="text-right text-danger">Crédito</th>
                      <th class="text-right">Saldo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in movimento.items" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.conta ? item.conta.numero : "" }} - {{ item.conta ? item.conta.designacao : "" }}</td>
                      <!-- <td>{{ item.contrapartida ? item.contrapartida.sub_conta : "" }}</td> -->
                      <td>{{ item.subconta.numero }} - {{ item.subconta.designacao }}</td>
                      <td>{{ item.tipo_movimento ? item.tipo_movimento.designacao : "#" }}</td>
                      <!-- <td>{{ item.tipo_credito ? item.tipo_credito.designacao : "#" }}</td> -->
                      <!-- <td>{{ item.tipo_proveito ? item.tipo_proveito.designacao : "#" }}</td> -->
                      <!-- <td>{{ item.contrapartida ? (item.contrapartida.sub_conta ? item.contrapartida.sub_conta.designacao : "#")  : "#" }}</td> -->
                      <td>{{ item.documento ? item.documento.designacao : "#" }}</td>
                      
                      <td class="text-right text-primary">{{ item.debito == 0 ? "-" : formatarValorMonetario(item.debito) }}</td>
                      <td class="text-right text-danger">{{ item.credito == 0 ? "-" : formatarValorMonetario(item.credito) }}</td>
                      
                      <td class="text-right text-primary" v-if="item.debito > item.credito">{{ formatarValorMonetario(item.debito - item.credito) }}</td>
                      <td class="text-right text-danger" v-if="item.credito > item.debito">{{ formatarValorMonetario(item.credito - item.debito) }}</td>
                      <td class="text-right" v-else></td>
                    </tr>
                  </tbody>
                  <!-- <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <th>{{ formatValor(movimento.debito) }}</th>
                      <th>{{ formatValor(movimento.credito) }}</th>
                      <th>{{ formatValor(movimento.iva) }}</th>
                    </tr>
                  </tfoot> -->
                </table>
              </div>
              
              <div class="card-footer">
                
              </div>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <p class="lead">Descrição</p>
                    
                    <p>{{ movimento.descricao }}</p>
                  </div>

                  <div class="col-6">
                    <p class="lead">Resumo</p>
                    <div class="table-responsive">
                      <table class="table">
                        <tr>
                          <th style="width: 50%">Total Débito:</th>
                          <td>{{ formatarValorMonetario(resultado.debito) }} <small>{{ sessions.moeda.base.sigla ?? "" }}</small></td>
                        </tr>
                        <tr>
                          <th>Total Crédito:</th>
                          <td>{{ formatarValorMonetario(resultado.credito) }} <small>{{ sessions.moeda.base.sigla ?? "" }}</small></td>
                        </tr>
                        <tr>
                          <th>Total IVA:</th>
                          <td>{{ formatarValorMonetario(resultado.iva) }} <small>{{ sessions.moeda.base.sigla ?? "" }}</small></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                    <div class="col-12 col-md-12">
                        <!-- <button type="button" class="btn btn-info float-left"><i class="fas fa-print"></i> Imprimir </button> -->
                        <!-- <a href="" class="btn btn-sm btn-success mr-2"><i class="fas fa-edit"></i> Editar</a> -->
                        <a class="btn btn-sm btn-danger mr-2" @click="imprimirDetalhePDF()"><i class="fas fa-file-pdf"></i> Imprimir</a>
                    </div>
                </div>
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
  props: ["movimento", "resultado"],
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
      movimento_id:  this.movimento.id,
    };
  },
  mounted() {},
  methods: {
  
        
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
    
    imprimirDetalhePDF(){
      window.open(`imprimir-fluxo-caixa-detalhe?movimento_id=${this.movimento_id}`);
    },
    
    formatValor(atual) {
      // Converte o valor para um número com duas casas decimais
      const valor = Number(atual).toFixed(2);
    
      // Formata o valor para a moeda especificada (AOA)
      const valorFormatado = Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "AOA",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }).format(valor);
    
      return valorFormatado;
    },
  },
};
</script>
  
  