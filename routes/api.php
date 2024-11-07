<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Middleware\AuthUser;
use Illuminate\Support\Facades\Route;

// API Code
Route::resource('/', HomeController::class)->only(['index']);

Route::resource('/login', AuthenticatedSessionController::class)->only(['store', 'destroy']);

Route::resource('/profile', RegisteredUserController::class)->only(['store']);

Route::resource('/produto', ProdutoController::class)->only(['index', 'show']);

Route::resource('/categoria', CategoriaController::class)->only(['index']);

Route::middleware([AuthUser::class])->group(function () {
    Route::resource('/profile', RegisteredUserController::class)->only(['show', 'update', 'destroy']);

    Route::resource('/carrinho', CarrinhoController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalCart']);

    Route::resource('/pedido', PedidoController::class)->only(['index', 'show', 'store', 'update']);
});

require __DIR__ . '/auth.php';
