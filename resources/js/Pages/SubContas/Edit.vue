<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">EDITAR SUBCONTAS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/sub-contas">Listagem</a></li>
              <li class="breadcrumb-item active">Editar subcontas</li>
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
                      <label for="codigo" class="form-label">Código de subConta</label>
                      <input type="text" id="codigo" v-model="form.codigo" class="form-control" placeholder="Ex: 1.1, 1.1.1">
                      <span class="text-danger" v-if="form.errors && form.errors.codigo">{{ form.errors.codigo }}</span>
                    </div>
                  
                    <div class="col-12 col-md-6 mb-4">
                      <label for="nome" class="form-label">SubConta</label>
                      <input type="text" id="nome" v-model="form.nome" class="form-control" placeholder="informe o nome da sub conta:">
                      <span class="text-danger" v-if="form.errors && form.errors.nome">{{ form.errors.nome }}</span>
                    </div>
                  
                  
                    <div class="col-12 col-md-6 mb-4">
                      <label for="" class="form-label">Conta</label>
                      <Select2 v-model="form.conta_id"
                        id="conta_id" class="col-12 col-md-12"
                        :options="contas" :settings="{ width: '100%' }" 
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.conta_id"
                        >{{ form.errors.conta_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="" class="form-label">Estados</label>
                      <Select2 v-model="form.status"
                        id="status" class="col-12 col-md-12"
                        :options="estados" :settings="{ width: '100%' }" 
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.status"
                        >{{ form.errors.status }}</span
                      >
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
  props: ["subconta", "contas"],
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
  },
  data() {
    return {
      estados: [
        {'id': "activo", 'text': "Activo"},
        {'id': "desactivo", 'text': "Desactivo"},
      ],
      
      form: {
        conta_id: this.subconta.conta_id ?? "",
        nome: this.subconta.nome ?? "",
        codigo: this.subconta.codigo ?? "",
        status: this.subconta.status ?? "",
        itemId: this.subconta.id ?? "",
      },
    };
  },
  mounted() {},
  methods: {
    submit() {
      this.$Progress.start();
      axios
        .put(`/sub-contas/${this.form.itemId}`, this.form)
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
            timer: 4000,
          });

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
            timer: 4000,
          });

          console.error("Erro ao fazer requisição POST:", error);
        });
    },
  },
};
</script>
    