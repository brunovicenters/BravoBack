<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

// API Code
Route::resource('/', HomeController::class)->only(['index']);

Route::resource('/login', AuthenticatedSessionController::class)->only(['store', 'destroy']);

Route::resource('/profile', RegisteredUserController::class)->only(['store', 'show', 'update', 'destroy']);

Route::resource('produto', ProdutoController::class)->only(['index', 'show']);

Route::resource('categoria', CategoriaController::class)->only(['index']);

require __DIR__ . '/auth.php';
