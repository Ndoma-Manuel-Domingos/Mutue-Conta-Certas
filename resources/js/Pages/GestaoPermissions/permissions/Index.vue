<template>
  <MainLayouts>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-uppercase">Permissões</h1>
          </div>

          <div class="col-sm-6"></div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5 class="float-left">Listagem permissões</h5>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table style="width: 100%" class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Designação</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in items.data" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.updated_at }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer">
                <Link href="" class="text-secondary">
                  Total Registro: {{ items.total }}</Link
                >
                <Paginacao
                  :links="items.links"
                  :prev="items.prev_page_url"
                  :next="items.next_page_url"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </MainLayouts>
</template>

<script>
import Paginacao from "../../../Shared/Paginacao.vue";
import { sweetSuccess, sweetError } from "../../../components/Alert";

export default {
  props: ["items", "roles"],
  components: { Paginacao },
  data() {
    return {
    };
  },
};
</script>
