<template>
    <MainLayoutsAdmin>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-info">Formulário de Pagamento de Licenças</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Dashboard</a></li>
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
                                <div class="card-body">

                                    <form class="row g-3" @submit.prevent="submit">

                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Empresa</label>
                                            <input type="text" v-model="empresa_usario.empresa.nome_empresa" disabled class="form-control"
                                                id="inputEmail4" placeholder="- - - -">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Licença</label>
                                            <input type="text" v-model="licenca.titulo" disabled class="form-control"
                                                id="inputEmail4" placeholder="- - - -">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Valor</label>
                                            <input type="text" v-model="licenca.preco" disabled class="form-control"
                                                id="inputEmail4" placeholder="00.000,00KZ">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Número de Operação
                                                Bancária</label>
                                            <input type="text" class="form-control"
                                                v-model="form.numero_operacao_bancaria" id="inputEmail4"
                                                placeholder="000.000.00">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputPassword4" class="form-label">Forma de Pagamento</label>
                                            <Select2 id="id" v-model="form.forma_pagamento" :options="forma_pagamento"
                                                :settings="{ width: '100%' }" />
                                        </div>

                                        <div class="col-6">
                                            <label for="inputAddress" class="form-label">Data Banco</label>
                                            <input type="date" class="form-control" v-model="form.data_banco"
                                                id="inputAddress" placeholder="1234 Main St">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="inputCity" class="form-label">Observação</label>
                                            <input type="text" class="form-control" v-model="form.observacao"
                                                id="inputCity" placeholder="Ex.: Numerário">
                                        </div>

                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Comprovativo</label>
                                            <input class="form-control" type="file" v-on:change="onChangeFotografia"
                                                id="formFile">

                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary"
                                                @click="submit(licenca)">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayoutsAdmin>
</template>

<script>

import MainLayoutsAdmin from '../../Layouts/MainLayoutsAdmin.vue';

export default {

    props: [
        'forma_pagamento', 'licenca', 'codigo_empresa', 'empresa_usario',
    ],
    components: { MainLayoutsAdmin },
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

            form: this.$inertia.form({
                numero_operacao_bancaria: "",
                forma_pagamento: "",
                data_banco: "",
                observacao: "",
                licenca_id: null,
                empresa_id: null,
                valor: null,
            }),
            comprovativo: null,

        };
    },
    mounted() {
        this.pegarDados();
        $('#table_licencas').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": true,
        });
    },
    created() {

        this.form.licenca_id = this.licenca.id;
        this.form.valor = this.licenca.preco;
        this.form.empresa_id = this.empresa_usario.empresa.id;

    },
    methods: {

        onChangeFotografia(event) {
            this.comprovativo = event.target.files[0];
        },
        deleteItem(item) {

            axios.delete(`/licenca/${item.id}`)
                .then((response) => {
                    this.$Progress.finish();
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        title: "Modulo Eliminado com Sucesso!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                    window.location.reload();
                })
                .catch((error) => {

                    this.$Progress.fail();
                    Swal.fire({
                        toast: true,
                        icon: "danger",
                        title: "Ocorreu um erro ao tantar eliminar o modulo!",
                        animation: false,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000
                    })

                });

            console.log(item.id)
        },
        pegarDados() {

        },

        submit(item) {

            let formData = new FormData();

            var editedItem = JSON.stringify(this.form);

            formData.append('form', editedItem);
            formData.append('comprovativo', this.comprovativo);
            formData.append('licecaId', this.licenca_id);


            this.$Progress.start();

            this.$inertia.post(route('ml.licencaPagamento'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }, {
                preverseScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.$Progress.finish();

                    Swal.fire({
                        title: "Bom Trabalho",
                        text: "Conta Criada com sucesso",
                        icon: "success",
                        confirmButtonColor: "#3d5476",
                        confirmButtonText: "Ok",
                        onClose: () => { },
                    });

                    location.reload();
                },
                onError: (errors) => {
                    console.log(errors);
                    this.$Progress.fail();
                },
            });

        },
        imprimirPeriodos() {
            window.open("imprimir-periodos");
        },

        ExportarExcelMunicipio() {
            window.open(
                `exportar-municipio-excel`
            );
        },
    },
};
</script>
