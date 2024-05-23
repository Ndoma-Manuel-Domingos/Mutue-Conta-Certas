<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login-api', [ApiController::class, 'loginAPI']);

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::post('/criar-subconta', [ApiController::class, 'criar_subconta']);
    Route::get('/listar-subconta', [ApiController::class, 'get_subconta']);
    Route::get('/listar-contas', [ApiController::class, 'get_conta']);
    Route::put('/actualizar-subconta/{id}', [ApiController::class, 'update_subconta']);
    
    Route::post('/criar-debito', [ApiController::class, 'criar_debito']);
    Route::post('/criar-credito', [ApiController::class, 'criar_credito']);
    Route::get('/listar-movimentos', [ApiController::class, 'lista_movimentos']);
});