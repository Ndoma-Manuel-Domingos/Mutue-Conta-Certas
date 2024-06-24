<template>
    <div>
      <line-chart :data="chartData" :library="chartOptions" :stacked="true" :download="true"></line-chart>
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  
  export default {
    data() {
      return {
        chartData: [],
        chartOptions: {
          colors: ['#3498db', '#e74c3c', '#2ecc71'], // Azul, Vermelho, Verde
          scales: {
            y: {
              ticks: {
                callback: function(value) {
                  return 'R$ ' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                }
              }
            }
          },
          tooltips: {
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                if (label) {
                  label += ': ';
                }
                label += 'R$ ' + tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                return label;
              }
            }
          }
        }
      }
    },
    mounted() {
      this.fetchData()
    },
    methods: {
      fetchData() {
        axios.get('/api/movimentos-mes')
          .then(response => {
            const data = response.data
            const chartData = []
  
            data.forEach(item => {
              chartData.push([`Mês ${item.mes} - Débito`, item.total_debito])
              chartData.push([`Mês ${item.mes} - Crédito`, item.total_credito])
              chartData.push([`Mês ${item.mes} - Saldo`, item.total_saldo])
            })
  
            this.chartData = chartData
          })
          .catch(error => {
            console.error('Erro ao buscar dados:', error)
          })
      }
    }
  }
  </script>
  
  <style scoped>
  </style>
  