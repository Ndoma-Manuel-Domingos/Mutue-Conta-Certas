<template>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <Link href="/administrativo" class="nav-link" :class="{ active: $page.component == 'Dashboard' }">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
                </Link>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" :class="{ active: $page.component.startsWith('AreaFinanceira/') }">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Empresas
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item" title="EMPRESAS">
                        <Link href="/listar-empresas" class="nav-link" :class="{
                    active: $page.component == 'Admin/Empresas/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listagem</p>
                        </Link>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" :class="{ active: $page.component.startsWith('AreaFinanceira/') }">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Gestão de Licenças
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item" title="Licença">
                        <Link href="/licenca" class="nav-link" :class="{
                    active: $page.component == 'Admin/Licenca/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Licenças</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="Licença">
                        <Link href="/modulo" class="nav-link" :class="{
                    active: $page.component == 'Admin/Modulo/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Módulos</p>
                        </Link>
                    </li>


                    <li class="nav-item" title="Licença-Usuário">
                        <Link href="/licenca-usuario" class="nav-link" :class="{
                    active: $page.component == 'Admin/LicencaUsuario/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Associar Licença a um Usuário</p>
                        </Link>
                    </li>

                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" :class="{ active: $page.component.startsWith('AreaFinanceira/') }">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Tabelas de Apoio
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    <li class="nav-item" title="REGIME DE EMPRESAS">
                        <Link href="/regime-empresa" class="nav-link" :class="{
                    active: $page.component == 'Admin/Regime/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Regime de Empresa</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="TIPOS DE EMPRESAS">
                        <Link href="/tipos-empresas" class="nav-link" :class="{
                    active: $page.component == 'Admin/TipoEmpresas/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipos de Empresas</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="GRUPOS DE EMPRESAS">
                        <Link href="/grupos-empresas" class="nav-link" :class="{
                    active: $page.component == 'Admin/GrupoEmpresas/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Grupos de Empresas</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="MOEDAS">
                        <Link href="/moeda" class="nav-link" :class="{
                    active: $page.component == 'Admin/Moeda/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Moedas de Operação</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="PAISES">
                        <Link href="/paises" class="nav-link" :class="{
                    active: $page.component == 'Admin/Paises/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Países</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="PROVINCIA">
                        <Link href="/provincia" class="nav-link" :class="{
                    active: $page.component == 'Admin/Provincia/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Provincias</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="MUNICIPIO">
                        <Link href="/municipio" class="nav-link" :class="{
                    active: $page.component == 'Admin/Municipio/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Municípios</p>
                        </Link>
                    </li>

                    <li class="nav-item" title="COMUNAS">
                        <Link href="/comuna" class="nav-link" :class="{
                    active: $page.component == 'Admin/Comuna/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Nova Comuna</p>
                        </Link>
                    </li>

                </ul>
            </li>


            <li class="nav-item">
                <a href="#" class="nav-link" :class="{ active: $page.component.startsWith('AreaFinanceira/') }">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Operadores
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item" title="Operadores">
                        <Link href="/operadores-admin" class="nav-link" :class="{
                    active: $page.component == 'Admin/Operadores/Index',
                }">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Operadores</p>
                        </Link>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },

    data: () => ({}),

    computed: {
        user() {
            return this.$page.props.auth.user;
        },
        sessions() {
            return this.$page.props.sessions.empresa_sessao;
        },
        sessions_exercicio() {
            return this.$page.props.sessions.empresa_sessao;
        },
    },
};
</script>


<style>
.nav-pills .nav-link.active,
.nav-pills .show>.nav-link {
    color: #fff;
    background-color: #52c7ed;
}
</style>
