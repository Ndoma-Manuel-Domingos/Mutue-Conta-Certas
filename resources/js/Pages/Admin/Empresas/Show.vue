<template>
  <MainLayoutsAdmin>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">DETALHES DE EMPRESA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/listar-empresas">Voltar</a></li>
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
              <div class="card-body table-responsive">
                <table class="table table-striped">
                 
                  <tbody>
                    
                    <tr>
                      <th>NIF</th>
                      <th>NOME</th>
                      <th style="width: 50px">ESTADO</th>
                      <th style="width: 50px">REGIME</th>
                    </tr>
                    
                    <tr>
                      <td style="width: 70px">{{ empresa.codigo_empresa }}</td>
                      <td style="width: 560px">{{ empresa.nome_empresa }}</td>
                      <td style="width: 50px">{{ empresa.estado_empresa_id == 1 ? "ACTIVO" : 'DESACTIVO' }}</td>
                      <td style="width: 50px">{{ empresa.regime.designacao }}</td>
                    </tr>
                    
                    <tr>
                      <th>MOEDA DE OPERAÇÃO</th>
                      <th>MOEDA ALTERNATIVA</th>
                      <th>TIPO</th>
                      <th>GRUPO</th>
                    </tr>
                    
                    <tr>
                      <td style="width: 100px">{{ empresa.moeda ? (empresa.moeda.base ? empresa.moeda.base.sigla : "") : "" }} - {{ empresa.moeda ? (empresa.moeda.base ? empresa.moeda.base.designacao : "") : "" }}</td>
                      <td style="width: 100px">{{ empresa.moeda ? (empresa.moeda.alternativa ? empresa.moeda.alternativa.sigla : "") : "" }} - {{ empresa.moeda ? (empresa.moeda.alternativa ? empresa.moeda.alternativa.designacao : "") : "" }}</td>
                      <td style="width: 100px">{{ empresa.tipo.designacao }}</td>
                      <td style="width: 100px">{{ empresa.grupo.designacao }}</td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          
          <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contactos</h3>
                        </div>
                      <div class="card-body table-responsive">
                        <table class="table table-striped">
                        
                          <tbody>
                            
                            <tr v-for="item in empresa.contactos" :key="item">
                              <th class="text-uppercase">{{ item.contacto.designacao }}</th>
                              <td class="text-uppercase text-right">{{ item.contacto_empresas }}</td>
                            </tr>
                        
                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Documentos</h3>
                        </div>
                      <div class="card-body table-responsive">
                        <table class="table table-striped">
                        
                          <tbody>
                            
                            <tr v-for="item in empresa.documentos" :key="item">
                              <th class="text-uppercase">{{ item.documento.designacao }}</th>
                              <td class="text-uppercase text-right">{{ item.numero_documento_empresa }}</td>
                            </tr>
                        
                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
                
            </div>
          </div>
          
          <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Endereço</h3>
                </div>
              <div class="card-body table-responsive">
                <table class="table table-striped">
                
                  <tbody>
                    
                    <tr>
                      <th>PAÍS</th>
                      <th>PROVÍNCIA</th>
                      <th>MUNICÍPIO</th>
                      <th>COMUNA</th>
                    </tr>
                    
                    <tr>
                      <td>{{ empresa.endereco ? (empresa.endereco.pais ? empresa.endereco.pais.designacao : "") : "" }}</td>
                      <td>{{ empresa.endereco ? (empresa.endereco.provincia ? empresa.endereco.provincia.designacao : "") : "" }}</td>
                      <td>{{ empresa.endereco ? (empresa.endereco.municipio ? empresa.endereco.municipio.designacao : "") : "" }}</td>
                      <td>{{ empresa.endereco ? (empresa.endereco.comuna ? empresa.endereco.comuna.designacao : "") : "" }}</td>
                    </tr>
                    
                    <tr>
                      <th>BAIRRO</th>
                      <th>RUA</th>
                      <th>CASA</th>
                      <th>CÓDIGO POSTAL</th>
                    </tr>
                    
                    <tr>
                      <td>{{ empresa.endereco ? empresa.endereco.bairro  : "" }}</td>
                      <td>{{ empresa.endereco ? empresa.endereco.rua  : "" }}</td>
                      <td>{{ empresa.endereco ? empresa.endereco.casa : "" }}</td>
                      <td>{{ empresa.endereco ? empresa.endereco.codigo_postal : "" }}</td>
                    </tr>
                    
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          

        </div>
      </div>
    </div>
  </MainLayoutsAdmin>
</template>
  
<script>

import MainLayoutsAdmin from "../../Layouts/MainLayoutsAdmin.vue";

export default {
  props: ["empresa"],
  components: { MainLayoutsAdmin, },
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
    return {};
  },

  mounted() {},
  methods: {
    verificar_sessao_empresa(item) {
      return this.sessions && this.sessions.id == item.id ? true : false;
    },
  },
};
</script>
  
  