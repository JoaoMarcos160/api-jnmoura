<?php

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
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace("API")->group(function () {
    Route::prefix("contatos")->group(function () {
        Route::get("/{id}", "ContatosController@show")->name("unico_contato");
        Route::get("/", "ContatosController@index")->name("index_contatos");
        Route::post("/listar", "ContatosController@listar_contatos")->name("listar_contatos");
        Route::post("/buscar", "ContatosController@buscar_contato")->name("buscar_contato");
        Route::post("/", "ContatosController@criar")->name("criar_contatos");
        Route::put("/", "ContatosController@alterar")->name("alterar_contatos");
        Route::delete("/", "ContatosController@deletar")->name("deletar_contatos");
    });
});

Route::get("/ok", function () {
    //função só pra conferir se a api está online
    return ["status" => true];
});
