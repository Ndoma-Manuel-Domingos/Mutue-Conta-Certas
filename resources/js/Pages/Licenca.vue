<template>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="content-wrapper kanban">
      <section class="content-header">
        <div class="container-fluid">
          <!-- <div class="row">
            <div class="col-sm-6">
              <h1>Kanban Board</h1>
            </div>
            <div class="col-sm-6 d-none d-sm-block">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Kanban Board</li>
              </ol>
            </div>
          </div> -->
        </div>
      </section>
      <section class="content">
        <div class="container-fluid h-100">
          <div v-for="item in licencas" :key="item.id">
            <div class="card card-row" :class="item.card">
              <div class="card-header">
                <h3 class="card-title">{{ item.titulo }}</h3>
              </div>
              <div class="card-body">
                <div class="card card-info card-outline">
                  <div class="card-header">
                    <h5 class="card-title">Modulos</h5>
                  </div>
                  <div class="card-body">
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox1"
                        disabled
                      />
                      <label for="customCheckbox1" class="custom-control-label"
                        >Bug</label
                      >
                    </div>
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox2"
                        disabled
                      />
                      <label for="customCheckbox2" class="custom-control-label"
                        >Feature</label
                      >
                    </div>
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox3"
                        disabled
                      />
                      <label for="customCheckbox3" class="custom-control-label"
                        >Enhancement</label
                      >
                    </div>
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox4"
                        disabled
                      />
                      <label for="customCheckbox4" class="custom-control-label"
                        >Documentation</label
                      >
                    </div>
                    <div class="custom-control custom-checkbox">
                      <input
                        class="custom-control-input"
                        type="checkbox"
                        id="customCheckbox5"
                        disabled
                      />
                      <label for="customCheckbox5" class="custom-control-label"
                        >Examples</label
                      >
                    </div>
                  </div>
                </div>
                <div class="card card-light card-outline">
                  <div class="card-header">
                    <h5 class="card-title">Descrição</h5>
                  </div>
                  <div class="card-body">
                    <p>
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                      Aenean commodo ligula eget dolor. Aenean massa. Cum sociis
                      natoque penatibus et magnis dis parturient montes,
                      nascetur ridiculus mus.
                    </p>
                  </div>
                </div>
                <div class="card card-light card-outline">
                  <div class="card-body">
                    <a :href="`assinar-contrato-licencas/${ item.id }`" class="btn btn-lg d-block" :class="item.btn"
                      >Assinar</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </body>
</template>

<script>
import AuthLayouts from "./Layouts/AuthLayouts.vue";
export default {
  layout: AuthLayouts,
  props: ["licencas"],
};
</script>

