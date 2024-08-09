<?php

use App\Http\Controllers\{
    ApuramentoResultadoController,
    AuthController,
    BalanceteController,
    BalancoController,
    ClassificacaoImobilizadosController,
    ClasseController,
    ContaController,
    DashboardController,
    DiarioController,
    EmpresaController,
    ExercicioController,
    MovimentoController,
    PeriodoController,
    PlanoGeralContaController,
    SubContaController,
    TipoDocumentoController,
    RegimeController,
    MoedaController,
    PaisesController,
    MunicipioController,
    ComunaController,
    ContrapartidaController,
    ExtratoContaController,
    FluxoCaixaController,
    GrupoEmpresaController,
    DocumentoController,
    ProvinciaController,
    TipoCreditoController,
    TipoEmpresaController,
    TipoMovimentoController,
    TipoProveitoController,
    UtilizadorController,
    CentroDeCustoController,
    FacturaController,
    ImobilizadosController,
    OperadorController,
    TabelaAmortizacaoController,
    TabelaAmortizacaoItemsController,
    LicencaUsuarioController,
};
use App\Http\Controllers\Admin\{
    EmpresaController as AdminEmpresaController,
    LicencaController,
    ModuloController,
    OperadorController as AdminOperadorController
};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [AuthController::class, 'login'])
->middleware('guest');

Route::get('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login_store'])->name('mc.login');
Route::get('/criar-conta', [AuthController::class, 'register'])->middleware('guest')->name('register');
Route::post('/criar-conta', [AuthController::class, 'register_store'])->name('mc.register');

Route::group(["middleware" => "auth"], function () {

    Route::get('/licencas', [LicencaController::class, 'licencas']);
    Route::get('/assinar-contrato-licencas/{id}', [LicencaController::class, 'pagamentoLicenca']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('mf.logout');
    Route::resource('classes', ClasseController::class);
    Route::resource('plano-geral-contas', PlanoGeralContaController::class);
    Route::resource('exercicios', ExercicioController::class);
    Route::get('/get-info-exercicio/{id}', [ExercicioController::class, 'get_info']);
    Route::get('/exercicios/iniciar-sessão/{id}', [ExercicioController::class, 'iniciar_sessao']);
    Route::post('/logout-exercicios', [ExercicioController::class, 'logout_exercicio'])->name('mf.logout_exercicio');
    Route::resource('periodos', PeriodoController::class);
    Route::get('/get-periodos/{id}', [PeriodoController::class, 'get_periodos']);
    Route::get('/get-info-periodo/{id}', [PeriodoController::class, 'get_info']);
    Route::resource('contas', ContaController::class);
    Route::get('/get-conta/{id}', [ContaController::class, 'get_conta']);
    Route::get('/get-subconta/{id}', [ContrapartidaController::class, 'get_subconta']);
    Route::resource('sub-contas', SubContaController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::get('/empresas-mudar-estado/{id}', [EmpresaController::class, 'mudar_estado']);
    Route::get('/empresas-imprimir-pdf', [EmpresaController::class, 'pdf']);
    Route::get('/empresas-imprimir-excel', [EmpresaController::class, 'excel']);
    Route::get('/empresas-duplicar/{id}', [EmpresaController::class, 'duplicar']);
    Route::put('/empresas-duplicar/{id}', [EmpresaController::class, 'duplicar_update']);

    Route::get('/empresas/iniciar-sessão/{id}', [EmpresaController::class, 'iniciar_sessao']);
    Route::post('/logout-empresa', [EmpresaController::class, 'logout_empresa'])->name('mf.logout_empresa');
    Route::get('/escolher-empresa-operar', [EmpresaController::class, 'escolher_empresa_operar']);

    Route::resource('imobilizados', ImobilizadosController::class);
    Route::get('/mapa-amortizacoes', [ImobilizadosController::class, 'mapa_amortizacoes']);
    Route::get('/imprimir-mapa-amortizacoes', [ImobilizadosController::class, 'imprimir_mapa_amortizacoes']);
    Route::resource('categorias-imobilizados', ClassificacaoImobilizadosController::class);
    Route::resource('tabela-amortizacoes', TabelaAmortizacaoController::class);
    Route::resource('tabela-amortizacoes-items', TabelaAmortizacaoItemsController::class);
    Route::get('/get-dados-tabela-amortizacoes-items/{id}', [TabelaAmortizacaoItemsController::class, 'get_dados_item']);
    Route::get('/get-items-tabela-amortizacoes/{id}', [TabelaAmortizacaoItemsController::class, 'get_items']);
    Route::get('/get-dados-sub-contas/{id}', [TabelaAmortizacaoItemsController::class, 'get_dados_sub_conta']);
    Route::get('/imprimir-ficha-imobilizado', [ImobilizadosController::class, 'imprimirFichaImobilizado']);
    Route::resource('diarios', DiarioController::class);
    Route::get('/get-diario/{id}', [DiarioController::class, 'get_diario']);
    Route::resource('tipos-documentos', TipoDocumentoController::class);
    Route::resource('movimentos', MovimentoController::class);

    Route::get('/facturas/painel', [FacturaController::class, 'home']);
    Route::resource('facturas', FacturaController::class);

    Route::resource('fluxos-caixas', FluxoCaixaController::class);
    Route::post('/adicionar-fluxo-caixa', [FluxoCaixaController::class, 'adicionar_fluxo_caixa']);
    Route::put('/editar-fluxo-caixa/{id}', [FluxoCaixaController::class, 'editar_fluxo_caixa']);
    Route::get('/remover-fluxo-caixa/{id}', [FluxoCaixaController::class, 'remover_fluxo_caixa']);
    Route::get('/demonstracao-fluxo-caixa', [FluxoCaixaController::class, 'demonstracaoFluxoCaixa']);
    Route::get('/demonstracao-fluxo-caixa-detalhe', [FluxoCaixaController::class, 'demonstracaoFluxoCaixaDetalhe']);
    Route::get('/fluxos-caixas-imprimir-nota-entregue', [FluxoCaixaController::class, 'imprimirNotaEntregue']);

    Route::resource('balancetes', BalanceteController::class);
    Route::resource('balancos', BalancoController::class);
    Route::resource('extratos-contas', ExtratoContaController::class);
    Route::resource('apuramento-resultados', ApuramentoResultadoController::class);
    Route::get('apuramento-resultado-pesquisa', [ApuramentoResultadoController::class, 'index']);
    Route::get('/adicionar-conta-movimento/{id}', [MovimentoController::class, 'adicionar_conta_movimento']);
    Route::get('/remover-conta-movimento/{id}', [MovimentoController::class, 'remover_conta_movimento']);
    Route::get('/inverter-valores-movimento/{id}', [MovimentoController::class, 'inverter_valores_movimento']);
    Route::get('/alterar-debito-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_debito_conta_movimento']);
    Route::get('/alterar-credito-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_credito_conta_movimento']);
    Route::get('/alterar-iva-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_iva_conta_movimento']);
    Route::get('/alterar-descricao-conta-movimento/{id}/{descricao}', [MovimentoController::class, 'alterar_descricao_conta_movimento']);
    // Route::get('/movimentos', [OperacaoController::class, 'movimentos']);
    Route::resource('tipos-empresas', TipoEmpresaController::class);
    Route::resource('grupos-empresas', GrupoEmpresaController::class);

    Route::resource('utilizadores', UtilizadorController::class);
    Route::resource('operadores', OperadorController::class);
    Route::resource('operadores-admin', AdminOperadorController::class);

    // Rotas de impressao de documentos-Ednilson
    Route::get('imprimir-contas', [ContaController::class, 'imprimirContas']);
    Route::get('imprimir-sub-contas', [SubContaController::class, 'imprimirSubContas']);
    Route::get('imprimir-classes', [ClasseController::class, 'imprimirClasses']);
    Route::get('imprimir-exercicios', [ExercicioController::class, 'imprimirExercicios']);
    Route::get('imprimir-periodos', [PeriodoController::class, 'imprimirPeriodo']);
    Route::get('imprimir-plano', [PlanoGeralContaController::class, 'imprimirPlano']);
    Route::get('imprimir-diario', [DiarioController::class, 'imprimirDiario']);
    Route::get('imprimir-movimentos', [MovimentoController::class, 'imprimirMovimento']);
    Route::get('imprimir-paises', [PaisesController::class, 'imprimirPaises']);
    Route::get('imprimir-balancete', [BalanceteController::class, 'imprimirBalancete']);
    Route::get('imprimir-balanco', [BalancoController::class, 'imprimirBalanco']);
    Route::get('imprimir-extrato', [ExtratoContaController::class, 'imprimirExtrato']);
    Route::get('imprimir-fluxo-caixa-detalhe', [FluxoCaixaController::class, 'imprimirDetalhePDF']);
    Route::get('imprimir-fluxo-caixa', [FluxoCaixaController::class, 'imprimirPDF']);
    Route::get('imprimir-controlo-fluxo-caixa', [FluxoCaixaController::class, 'imprimircontrolePDF']);
    Route::get('imprimir-extrato-excel', [ExtratoContaController::class, 'imprimirExtratoExcel']);

    Route::get('get-subcontas', [BalancoController::class, 'getSubcontas']);

    // Rotas Tabela de apoio
    Route::resource('tipos-creditos', TipoCreditoController::class);
    Route::resource('tipos-proveitos', TipoProveitoController::class);
    Route::resource('contrapartidas', ContrapartidaController::class);
    Route::resource('tipos-movimentos', TipoMovimentoController::class);
    Route::resource('documentos', DocumentoController::class);
    Route::resource('regime-empresa', RegimeController::class);
    Route::resource('moeda', MoedaController::class);
    Route::resource('centro_de_custo', CentroDeCustoController::class);
    Route::resource('paises', PaisesController::class);
    Route::resource('municipio', MunicipioController::class);
    Route::resource('provincia', ProvinciaController::class);
    Route::resource('comuna', ComunaController::class);

    // Exportar Excell
    Route::get('exportar-balancete-excel', [BalanceteController::class, 'exportarExcel']);
    Route::get('exportar-movimento-excel', [MovimentoController::class, 'exportarExcel']);
    Route::get('exportar-diario-excel', [DiarioController::class, 'exportarExcel']);
    Route::get('exportar-tipo-documento-excel', [TipoDocumentoController::class, 'exportarExcel']);
    // Route::get('exportar-demonstracao-excel', [FluxoCaixaController::class, 'exportarExcel']);
    Route::get('exportar-demonstracao-detalhe-excel', [FluxoCaixaController::class, 'exportarExcel']);
    Route::get('exportar-exercicio-excel', [ExercicioController::class, 'exportarExcel']);
    Route::get('exportar-periodo-excel', [PeriodoController::class, 'exportarExcel']);
    Route::get('exportar-contra-partida-excel', [ContrapartidaController::class, 'exportarExcel']);
    Route::get('exportar-tipo-credito-excel', [TipoCreditoController::class, 'exportarExcel']);
    Route::get('exportar-tipo-movimento-excel', [TipoMovimentoController::class, 'exportarExcel']);
    Route::get('exportar-documento-excel', [DocumentoController::class, 'exportarExcel']);
    Route::get('exportar-tipo-proveito-excel', [TipoProveitoController::class, 'exportarExcel']);
    Route::get('exportar-regime-excel', [RegimeController::class, 'exportarExcel']);
    Route::get('exportar-tipo-empresa-excel', [TipoEmpresaController::class, 'exportarExcel']);
    Route::get('exportar-grupo-empresa-excel', [GrupoEmpresaController::class, 'exportarExcel']);
    Route::get('exportar-moeda-excel', [MoedaController::class, 'exportarExcel']);
    Route::get('exportar-pais-excel', [PaisesController::class, 'exportarExcel']);
    Route::get('exportar-provincia-excel', [ProvinciaController::class, 'exportarExcel']);
    Route::get('exportar-municipio-excel', [MunicipioController::class, 'exportarExcel']);
    Route::get('exportar-comuna-excel', [ComunaController::class, 'exportarExcel']);
    Route::get('exportar-operador-excel', [OperadorController::class, 'exportarExcel']);

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('mf.dashboard');

    /** ROUTAS DO ADMINISTRATOR */

    Route::get('/administrativo', [DashboardController::class, 'dashboard_admin'])->name('mf.dashboard-admin');
    Route::resource('licenca', LicencaController::class);
    Route::post('licenca-modulo', [LicencaController::class, 'licenca_modulo']);
    Route::get('licenca-modulo/{id}', [LicencaController::class, 'licenca_modulo_eliminar']);
    Route::resource('modulo', ModuloController::class);
    Route::get('/listar-empresas', [AdminEmpresaController::class, 'index']);
    Route::get('/detalhes-empresas/{id}', [AdminEmpresaController::class, 'show']);

    //licencas
    Route::get('licenca-utilizacao', [LicencaUsuarioController::class, 'licencaUtilizacao']);
    Route::get('licenca-sem-utilizacao', [LicencaUsuarioController::class, 'licencaSemUtilizacao']);

    Route::get('/api/movimentos-mes', [DashboardController::class, 'movimentosPorMes']);

    Route::resource('/licenca-usuario', LicencaUsuarioController::class);
    Route::get('/mudar-estado-licenca/{id}', [LicencaUsuarioController::class, 'mudaEstadoLicenca']);

    Route::get('assinar-licenca/{id}/{id_empresa}', [LicencaController::class, 'licencaPagamento']);
    Route::post('pagamento-licenca', [LicencaController::class, 'assinar_licencas'])->name('ml.licencaPagamento');

    Route::get('/licencas-escolha/{id}', [LicencaController::class, 'licencasEscolha'])->name('ml.rescolherLicenca');

});
