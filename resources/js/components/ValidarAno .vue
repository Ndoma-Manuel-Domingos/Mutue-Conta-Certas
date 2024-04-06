<template>
    <div>
      <label for="dataSelecionada">Selecione uma data do ano:</label>
      <input type="date" id="dataSelecionada" v-model="dataSelecionada" @change="validarData">
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        dataSelecionada: ""
      };
    },
    methods: {
      validarData() {
        // Obtém o ano informado
        const anoInformado = this.dataSelecionada ? new Date(this.dataSelecionada).getFullYear() : null;
  
        // Verifica se a data selecionada está dentro do ano informado
        if (anoInformado && anoInformado !== this.anoInformado) {
          alert("Por favor, selecione uma data válida dentro do ano informado.");
          // Limpa o campo de entrada de data
          this.dataSelecionada = "";
        }
      }
    },
    props: {
      anoInformado: {
        type: Number,
        required: true
      }
    }
  };
  </script>