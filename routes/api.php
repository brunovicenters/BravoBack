<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Code
Route::resource('/', HomeController::class)->only(['index']);

Route::resource('/login', AuthenticatedSessionController::class)->only(['store', 'destroy']);

Route::post('/profile', [RegisteredUserController::class, 'store']);

Route::resource('produto', ProdutoController::class)->only(['index', 'show']);

Route::resource('categoria', CategoriaController::class)->only(['index']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::resource('/profile', RegisteredUserController::class)->only(['show']);
// });

// Web Code

// Route::get('/', function () { acsum4DRVl5iwjAyu4BsOKWAeUkjNyH0gWVTFZCa
//     return view('welcome');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
