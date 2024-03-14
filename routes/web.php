<?php

use App\Http\Controllers\{
    AuthController,
    ClasseController,
    ContaController,
    DashboardController,
    EmpresaController,
    ExercicioController,
    OperacaoController,
    PeriodoController,
    PlanoGeralContaController,
    SubContaController
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
    
    Route::get('/diarios', [OperacaoController::class, 'diarios']);
    Route::get('/movimentos', [OperacaoController::class, 'movimentos']);
    Route::get('/balancetes', [OperacaoController::class, 'balancetes']);



    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('mf.dashboard');

});
