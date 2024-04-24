<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-info">CRIAR EMPRESA</h1>
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
                      <input
                        type="text"
                        id="nome_empresa"
                        v-model="form.nome_empresa"
                        class="form-control"
                        placeholder="Nome da Empresa"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.nome_empresa"
                        >{{ form.errors.nome_empresa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="codigo_empresa" class="form-label">NIF</label>
                      <input
                        type="text"
                        id="codigo_empresa"
                        v-model="form.codigo_empresa"
                        class="form-control"
                        placeholder="Número de Identificação Físcal"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.codigo_empresa"
                        >{{ form.errors.codigo_empresa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="descricao_empresa" class="form-label"
                        >Descrição</label
                      >
                      <input
                        type="text"
                        id="descricao_empresa"
                        v-model="form.descricao_empresa"
                        class="form-control"
                        placeholder="Descrição Empresa"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.descricao_empresa"
                        >{{ form.errors.descricao_empresa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="estado_empresa_id" class="form-label"
                        >Estado</label
                      >
                      <Select2
                        v-model="form.estado_empresa_id"
                        id="estado_empresa_id"
                        class="col-12 col-md-12"
                        :options="estados"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.estado_empresa_id"
                        >{{ form.errors.estado_empresa_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="logotipo_da_empresa" class="form-label"
                        >Descrição</label
                      >
                      <input
                        type="file"
                        id="logotipo_da_empresa"
                        accept="image/*"
                        class="form-control"
                        @input="previewImage($event)"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.logotipo_da_empresa"
                        >{{ form.errors.logotipo_da_empresa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <img
                        src=""
                        alt=""
                        id="image-preview"
                        style="height: 120px; width: 100%; display: none"
                      />
                    </div>
                  </div>
                </div>

                <div class="card-footer"></div>
              </div>

              <div class="card">
                <div class="card-header">
                  <h5>Regime do IVA/Moeda de Operação/Tipos e Grupos</h5>
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-md-3 mb-4">
                      <label for="regime_empresa_id" class="form-label"
                        >Regime do IVA</label
                      >
                      <Select2
                        v-model="form.regime_empresa_id"
                        id="regime_empresa_id"
                        class="col-12 col-md-12"
                        :options="regimes"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.regime_empresa_id"
                        >{{ form.errors.regime_empresa_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="moeda_base_id" class="form-label"
                        >Moeda de Operação</label
                      >
                      <Select2
                        v-model="form.moeda_base_id"
                        id="moeda_base_id"
                        class="col-12 col-md-12"
                        :options="moedas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.moeda_base_id"
                        >{{ form.errors.moeda_base_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="moeda_alternativa_id" class="form-label"
                        >Moeda Alternativa</label
                      >
                      <Select2
                        v-model="form.moeda_alternativa_id"
                        id="moeda_alternativa_id"
                        class="col-12 col-md-12"
                        :options="moedas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.moeda_alternativa_id"
                        >{{ form.errors.moeda_alternativa_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="tipo_empresa_id" class="form-label"
                        >Tipos</label
                      >
                      <Select2
                        v-model="form.tipo_empresa_id"
                        id="tipo_empresa_id"
                        class="col-12 col-md-12"
                        :options="tipos_empresas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.tipo_empresa_id"
                        >{{ form.errors.tipo_empresa_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-2 mb-4">
                      <label for="grupo_empresa_id" class="form-label"
                        >Grupos</label
                      >
                      <Select2
                        v-model="form.grupo_empresa_id"
                        id="grupo_empresa_id"
                        class="col-12 col-md-12"
                        :options="grupos_empresas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.grupo_empresa_id"
                        >{{ form.errors.grupo_empresa_id }}</span
                      >
                    </div>
                  </div>
                </div>

                <div class="card-footer"></div>
              </div>

              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="card">
                    <div class="card-header">
                      <h5>Contactos</h5>
                    </div>
                    <div class="card-body">
                      <div
                        class="row"
                        v-for="item in form.contactos_empresa"
                        :key="item"
                      >
                        <div class="col-12 col-md-6 mb-4">
                          <label
                            for="tipo_contacto_empresa_id"
                            class="form-label"
                            >Tipos Contactos</label
                          >
                          <Select2
                            v-model="item.tipo_contacto_empresa_id"
                            id="tipo_contacto_empresa_id"
                            class="col-12 col-md-12"
                            :options="tipos_contactos_empresa"
                            :settings="{ width: '100%' }"
                          />
                          <span
                            class="text-danger"
                            v-if="
                              form.errors &&
                              form.errors.tipo_contacto_empresa_id
                            "
                            >{{ form.errors.tipo_contacto_empresa_id }}</span
                          >
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                          <label
                            for="tipo_contacto_empresa_designacao"
                            class="form-label"
                            >Designação</label
                          >
                          <input
                            type="text"
                            id="tipo_contacto_empresa_designacao"
                            v-model="item.tipo_contacto_empresa_designacao"
                            class="form-control"
                            placeholder="Designação"
                          />
                          <span
                            class="text-danger"
                            v-if="
                              form.errors &&
                              form.errors.tipo_contacto_empresa_designacao
                            "
                            >{{
                              form.errors.tipo_contacto_empresa_designacao
                            }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button
                        class="btn btn-sm btn-primary"
                        @click="adicionar_campos_tipos_contactos"
                      >
                        Adicionar mais contactos
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="card">
                    <div class="card-header">
                      <h5>Tipos de Documentos</h5>
                    </div>
                    <div class="card-body">
                      <div
                        class="row"
                        v-for="item in form.documentos_empresa"
                        :key="item"
                      >
                        <div class="col-12 col-md-6 mb-4">
                          <label
                            for="tipo_documento_empresa_id"
                            class="form-label"
                            >Tipos Documentos</label
                          >
                          <Select2
                            v-model="item.tipo_documento_empresa_id"
                            id="tipo_documento_empresa_id"
                            class="col-12 col-md-12"
                            :options="tipos_documentos_empresa"
                            :settings="{ width: '100%' }"
                          />
                          <span
                            class="text-danger"
                            v-if="
                              form.errors &&
                              form.errors.tipo_documento_empresa_id
                            "
                            >{{ form.errors.tipo_documento_empresa_id }}</span
                          >
                        </div>

                        <div class="col-12 col-md-6 mb-4">
                          <label
                            for="tipo_documento_empresa_designacao"
                            class="form-label"
                            >Designação</label
                          >
                          <input
                            type="text"
                            id="tipo_documento_empresa_designacao"
                            v-model="item.tipo_documento_empresa_designacao"
                            class="form-control"
                            placeholder="Designação"
                          />
                          <span
                            class="text-danger"
                            v-if="
                              form.errors &&
                              form.errors.tipo_documento_empresa_designacao
                            "
                            >{{
                              form.errors.tipo_documento_empresa_designacao
                            }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button
                        class="btn btn-sm btn-primary"
                        @click="adicionar_campos_tipos_documentos"
                      >
                        Adicionar mais documentos
                      </button>
                    </div>
                  </div>
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
                      <Select2
                        v-model="form.pais_id"
                        id="pais_id"
                        class="col-12 col-md-12"
                        :options="paises"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.pais_id"
                        >{{ form.errors.pais_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="provincia_id" class="form-label"
                        >Províncias</label
                      >
                      <Select2
                        v-model="form.provincia_id"
                        id="provincia_id"
                        class="col-12 col-md-12"
                        :options="provincias"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.provincia_id"
                        >{{ form.errors.provincia_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="municipio_id" class="form-label"
                        >Municípios</label
                      >
                      <Select2
                        v-model="form.municipio_id"
                        id="municipio_id"
                        class="col-12 col-md-12"
                        :options="municipios"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.municipio_id"
                        >{{ form.errors.municipio_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-6 mb-4">
                      <label for="comuna_id" class="form-label">Comunas</label>
                      <Select2
                        v-model="form.comuna_id"
                        id="comuna_id"
                        class="col-12 col-md-12"
                        :options="comunas"
                        :settings="{ width: '100%' }"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.comuna_id"
                        >{{ form.errors.comuna_id }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="rua" class="form-label">Rua</label>
                      <input
                        type="text"
                        id="rua"
                        v-model="form.rua"
                        class="form-control"
                        placeholder="Rua"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.rua"
                        >{{ form.errors.rua }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="casa" class="form-label"
                        >Nº do Edifício</label
                      >
                      <input
                        type="text"
                        id="casa"
                        v-model="form.casa"
                        class="form-control"
                        placeholder="Casa"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.casa"
                        >{{ form.errors.casa }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="bairro" class="form-label">Bairro</label>
                      <input
                        type="text"
                        id="bairro"
                        v-model="form.bairro"
                        class="form-control"
                        placeholder="bairro"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.bairro"
                        >{{ form.errors.bairro }}</span
                      >
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                      <label for="codigo_postal" class="form-label"
                        >Código Postal</label
                      >
                      <input
                        type="text"
                        id="codigo_postal"
                        v-model="form.codigo_postal"
                        class="form-control"
                        placeholder="Código Postal"
                      />
                      <span
                        class="text-danger"
                        v-if="form.errors && form.errors.codigo_postal"
                        >{{ form.errors.codigo_postal }}</span
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
  props: [
    "paises",
    "provincias",
    "municipios",
    "comunas",
    "moedas",
    "regimes",
    "tipos_documentos_empresa",
    "tipos_contactos_empresa",
    "tipos_empresas",
    "grupos_empresas",
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
        { id: 1, text: "Activo" },
        { id: 2, text: "Desactivo" },
      ],

      form: this.$inertia.form({
        nome_empresa: "",
        codigo_empresa: "",
        descricao_empresa: "",
        estado_empresa_id: "1",
        moeda_base_id: "",
        moeda_alternativa_id: "",
        regime_empresa_id: "",
        moeda_cambio_id: "",

        logotipo_da_empresa: "",

        tipo_empresa_id: "",
        grupo_empresa_id: "",

        contactos_empresa: [
          {
            tipo_contacto_empresa_id: "",
            tipo_contacto_empresa_designacao: "",
          },
        ],

        documentos_empresa: [
          {
            tipo_documento_empresa_id: "",
            tipo_documento_empresa_designacao: "",
          },
        ],

        rua: "",
        casa: "",
        bairro: "",
        codigo_postal: "",
        pais_id: "",
        provincia_id: "",
        municipio_id: "",
        comuna_id: "",
      }),
    };
  },
  mounted() {},
  methods: {
    adicionar_campos_tipos_documentos() {
      this.form.documentos_empresa.push({
        tipo_documento_empresa_id: "",
        tipo_documento_empresa_designacao: "",
      });
      event.preventDefault();
    },

    adicionar_campos_tipos_contactos() {
      this.form.contactos_empresa.push({
        tipo_contacto_empresa_id: "",
        tipo_contacto_empresa_designacao: "",
      });
      event.preventDefault();
    },

    previewImage(event) {
      if (event.target.files.length > 0) {
        this.form.logotipo_da_empresa = event.target.files[0];

        var src = URL.createObjectURL(event.target.files[0]);
        var image_preview = document.getElementById("image-preview");

        image_preview.src = src;
        image_preview.style.display = "block";
      }
    },

    submit() {
      this.$Progress.start();

      this.form.post(route("empresas.store"), {
        preverseScroll: true,
        onSuccess: (response) => {
          Swal.fire({
            toast: true,
            icon: "success",
            title: "Dados Salvos com Sucesso!",
            animation: false,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
          });
          this.form.reset();
          window.location.reload();
          console.log("Resposta da requisição POST:", response.data);
        },
        onError: (error) => {
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
        },
      });
    },
  },
};
</script>
    