<?php

use App\Http\Controllers\{
    AuthController,
    ClasseController,
    ContaController,
    DashboardController,
    DiarioController,
    EmpresaController,
    ExercicioController,
    MovimentoController,
    OperacaoController,
    PeriodoController,
    PlanoGeralContaController,
    SubContaController,
    TipoDocumentoController
};
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

    Route::post('/logout', [AuthController::class, 'logout'])->name('mf.logout');
    Route::resource('classes', ClasseController::class);
    Route::resource('plano-geral-contas', PlanoGeralContaController::class);
    Route::resource('exercicios', ExercicioController::class);
    Route::resource('periodos', PeriodoController::class);
    Route::resource('contas', ContaController::class);
    Route::get('/get-conta/{id}', [ContaController::class, 'get_conta']);
    Route::resource('sub-contas', SubContaController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::get('/empresas/iniciar-sessão/{id}', [EmpresaController::class, 'iniciar_sessao']);
    Route::post('/logout-empresa', [EmpresaController::class, 'logout_empresa'])->name('mf.logout_empresa');
    Route::get('/escolher-empresa-operar', [EmpresaController::class, 'escolher_empresa_operar']);
    
    Route::resource('diarios', DiarioController::class);
    Route::get('/get-diario/{id}', [DiarioController::class, 'get_diario']);
    Route::resource('tipos-documentos', TipoDocumentoController::class);
    Route::resource('movimentos', MovimentoController::class);
    Route::get('/adicionar-conta-movimento/{id}', [MovimentoController::class, 'adicionar_conta_movimento']);
    Route::get('/remover-conta-movimento/{id}', [MovimentoController::class, 'remover_conta_movimento']);
    Route::get('/alterar-debito-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_debito_conta_movimento']);
    Route::get('/alterar-credito-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_credito_conta_movimento']);
    // Route::get('/alterar-iva-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_iva_conta_movimento']);
    // Route::get('/alterar-descricao-conta-movimento/{id}/{valor}', [MovimentoController::class, 'alterar_descricao_conta_movimento']);
    // Route::get('/movimentos', [OperacaoController::class, 'movimentos']);
    Route::get('/balancetes', [OperacaoController::class, 'balancetes']);



    // Rotas de impressao de documentos-Ednilson
    Route::get('imprimir-contas', [ContaController::class, 'imprimirContas']);
    Route::get('imprimir-sub-contas', [SubContaController::class, 'imprimirSubContas']);
    Route::get('imprimir-classes', [ClasseController::class, 'imprimirClasses']);
    Route::get('imprimir-exercicios', [ExercicioController::class, 'imprimirExercicios']);
    Route::get('imprimir-periodos', [PeriodoController::class, 'imprimirPeriodo']); 
    Route::get('imprimir-plano', [PlanoGeralContaController::class, 'imprimirPlano']);

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('mf.dashboard');

});
