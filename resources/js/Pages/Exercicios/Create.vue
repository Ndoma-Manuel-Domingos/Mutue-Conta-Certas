<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">CRIAR EXERCÍCIO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/exercicios">Listagem</a></li>
              <li class="breadcrumb-item active">Criar Exercício</li>
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
                <div class="card-header"></div>

                <div class="card-body">
                  <div class="row">
                  
                    <div class="col-12 col-md-6 mb-4">
                      <label for="designacao" class="form-label">Designação</label>
                      <input type="text" class="form-control" placeholder="Designação. Ex: 2024" v-model="form.designacao">
                     
                      <span class="text-danger" v-if="form.errors && form.errors.designacao">{{ form.errors.designacao }}</span>
                    </div>
                  
                    <!-- <div class="col-12 col-md-6 mb-4">
                      <label for="classe_id" class="form-label">Classes</label>
                      <Select2 v-model="form.classe_id"
                        id="classe_id" class="col-12 col-md-12"
                        :options="classes" :settings="{ width: '100%' }" 
                      />
                      <span class="text-danger" v-if="form.errors && form.errors.classe_id">{{ form.errors.classe_id }}</span>
                    </div> -->

                    <div class="col-12 col-md-6 mb-4">
                      <label for="estado" class="form-label">Estado</label>
                      <Select2 v-model="form.estado"
                        id="estado" class="col-12 col-md-12"
                        :options="estados" :settings="{ width: '100%' }" 
                      />
                      <span class="text-danger" v-if="form.errors && form.errors.estado">{{ form.errors.estado }}</span>
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
    "classes",
  ],
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
      
      estados: [
        {'id': "1", 'text': "Activo"},
        {'id': "2", 'text': "Desactivo"},
      ],
      
      form: {
        designacao: "",
        estado: "1",
      },
    };
  },
  mounted() {},
  methods: {
    submit() {
      this.$Progress.start();

      axios.post(route('exercicios.store'), this.form)
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
    