<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">CRIAR EMPRESA</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/empresas">Listagem</a></li>
              <li class="breadcrumb-item active">Criar Empresa</li>
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
                    <h5>Dados da Empresa</h5>
                  </div>
  
                  <div class="card-body">
                    <div class="row">
                      
                      <div class="col-12 col-md-6 mb-4">
                        <label for="nome_empresa" class="form-label">Nome</label>
                        <input type="text" id="nome_empresa" v-model="form.nome_empresa" class="form-control" placeholder="Nome da Empresa">
                        <span class="text-danger" v-if="form.errors && form.errors.nome_empresa">{{ form.errors.nome_empresa }}</span>
                      </div>
                       
                      <div class="col-12 col-md-6 mb-4">
                        <label for="codigo_empresa" class="form-label">NIF</label>
                        <input type="text" id="codigo_empresa" v-model="form.codigo_empresa" class="form-control" placeholder="Número de Identificação Físcal">
                        <span class="text-danger" v-if="form.errors && form.errors.codigo_empresa">{{ form.errors.codigo_empresa }}</span>
                      </div>
                      
                      <div class="col-12 col-md-6 mb-4">
                        <label for="descricao_empresa" class="form-label">Descrição</label>
                        <input type="text" id="descricao_empresa" v-model="form.descricao_empresa" class="form-control" placeholder="Descrição Empresa">
                        <span class="text-danger" v-if="form.errors && form.errors.descricao_empresa">{{ form.errors.descricao_empresa }}</span>
                      </div>
                
                      <div class="col-12 col-md-6 mb-4">
                        <label for="estado_empresa_id" class="form-label">Estado</label>
                        <Select2 v-model="form.estado_empresa_id"
                          id="estado_empresa_id" class="col-12 col-md-12"
                          :options="estados" :settings="{ width: '100%' }" 
                        />
                        <span class="text-danger" v-if="form.errors && form.errors.estado_empresa_id">{{ form.errors.estado_empresa_id }}</span>
                      </div>
                                            
                    </div>
                  </div>
                  
                  <div class="card-footer"></div>
                </div>
                
                <div class="card">
                  <div class="card-header">
                      <h5>Regime do IVA/Moeda de Operação</h5>
                    </div>
                    
                    <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-md-3 mb-4">
                          <label for="regime_empresa_id" class="form-label">Regime do IVA</label>
                          <Select2 v-model="form.regime_empresa_id"
                            id="regime_empresa_id" class="col-12 col-md-12"
                            :options="regimes" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.regime_empresa_id">{{ form.errors.regime_empresa_id }}</span>
                        </div>
                          
                        <div class="col-12 col-md-3 mb-4">
                          <label for="moeda_base_id" class="form-label">Moeda de Operação</label>
                          <Select2 v-model="form.moeda_base_id"
                            id="moeda_base_id" class="col-12 col-md-12"
                            :options="moedas" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.moeda_base_id">{{ form.errors.moeda_base_id }}</span>
                        </div>
                        
                        <!-- <div class="col-12 col-md-3 mb-4">
                          <label for="moeda_alternativa_id" class="form-label">Moeda Alternativa</label>
                          <Select2 v-model="form.moeda_alternativa_id"
                            id="moeda_alternativa_id" class="col-12 col-md-12"
                            :options="moedas" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.moeda_alternativa_id">{{ form.errors.moeda_alternativa_id }}</span>
                        </div> -->
                        
                        <!-- <div class="col-12 col-md-3 mb-4">
                          <label for="moeda_cambio_id" class="form-label">Moeda para Cambio</label>
                          <Select2 v-model="form.moeda_cambio_id"
                            id="moeda_cambio_id" class="col-12 col-md-12"
                            :options="moedas" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.moeda_cambio_id">{{ form.errors.moeda_cambio_id }}</span>
                        </div> -->
                        
                      </div>
                    </div>
  
                    <div class="card-footer">
                    </div>
                </div>
                
                <div class="card">
                  <div class="card-header">
                      <h5>Endereço</h5>
                    </div>
                    
                    <div class="card-body">
                      <div class="row">
                      
                        <div class="col-12 col-md-6 mb-4">
                          <label for="pais_id" class="form-label">País</label>
                          <Select2 v-model="form.pais_id"
                            id="pais_id" class="col-12 col-md-12"
                            :options="paises" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.pais_id">{{ form.errors.pais_id }}</span>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                          <label for="provincia_id" class="form-label">Províncias</label>
                          <Select2 v-model="form.provincia_id"
                            id="provincia_id" class="col-12 col-md-12"
                            :options="provincias" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.provincia_id">{{ form.errors.provincia_id }}</span>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                          <label for="municipio_id" class="form-label">Municípios</label>
                          <Select2 v-model="form.municipio_id"
                            id="municipio_id" class="col-12 col-md-12"
                            :options="municipios" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.municipio_id">{{ form.errors.municipio_id }}</span>
                        </div>
               
                        <div class="col-12 col-md-6 mb-4">
                          <label for="comuna_id" class="form-label">Comunas</label>
                          <Select2 v-model="form.comuna_id"
                            id="comuna_id" class="col-12 col-md-12"
                            :options="comunas" :settings="{ width: '100%' }" 
                          />
                          <span class="text-danger" v-if="form.errors && form.errors.comuna_id">{{ form.errors.comuna_id }}</span>
                        </div>
                        
                      
                        <div class="col-12 col-md-3 mb-4">
                          <label for="rua" class="form-label">Rua</label>
                          <input type="text" id="rua" v-model="form.rua" class="form-control" placeholder="Rua">
                          <span class="text-danger" v-if="form.errors && form.errors.rua">{{ form.errors.rua }}</span>
                        </div>
                        
                        <div class="col-12 col-md-3 mb-4">
                          <label for="casa" class="form-label">Nº do Edifício</label>
                          <input type="text" id="casa" v-model="form.casa" class="form-control" placeholder="Casa">
                          <span class="text-danger" v-if="form.errors && form.errors.casa">{{ form.errors.casa }}</span>
                        </div>
                        
                        <div class="col-12 col-md-3 mb-4">
                          <label for="bairro" class="form-label">Bairro</label>
                          <input type="text" id="bairro" v-model="form.bairro" class="form-control" placeholder="bairro">
                          <span class="text-danger" v-if="form.errors && form.errors.bairro">{{ form.errors.bairro }}</span>
                        </div>
                      
                        <div class="col-12 col-md-3 mb-4">
                          <label for="codigo_postal" class="form-label">Código Postal</label>
                          <input type="text" id="codigo_postal" v-model="form.codigo_postal" class="form-control" placeholder="Código Postal">
                          <span class="text-danger" v-if="form.errors && form.errors.codigo_postal">{{ form.errors.codigo_postal }}</span>
                        </div>
                      
                      
                        
                      </div>
                    </div>
  
                    <div class="card-footer">
                      <button class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar
                      </button>
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
    "paises",
    "provincias",
    "municipios",
    "comunas",
    "moedas",
    "regimes",
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
        {'id': 1, 'text': "Activo"},
        {'id': 2, 'text': "Desactivo"},
      ],
      
      form: {
        nome_empresa: "",
        codigo_empresa: "",
        descricao_empresa: "",
        estado_empresa_id: "",
        moeda_base_id: "",
        moeda_alternativa_id: "",
        regime_empresa_id: "",
        moeda_cambio_id: "",
        rua: "",
        casa: "",
        bairro: "",
        codigo_postal: "",
        pais_id: "",
        provincia_id: "",
        municipio_id: "",
        comuna_id: "",
      },
    };
  },
  mounted() {},
  methods: {
    submit() {
      this.$Progress.start();

      axios.post(route('empresas.store'), this.form)
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
  },
};
</script>
    