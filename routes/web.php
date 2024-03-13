<?php

use App\Http\Controllers\{
    AuthController,
    ClasseController,
    ContaController,
    DashboardController,
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
    Route::delete('classes/delete/{id}', [ClasseController::class, 'destroy']);
    Route::resource('contas', ContaController::class);
    Route::resource('sub-contas', SubContaController::class);

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('mf.dashboard');

});
