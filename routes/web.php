<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartamentosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\RegiaoController;
use App\Http\Controllers\EmpController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/vendas', [VendasController::class, 'index'])->name('vendas.index');
    Route::get('/dashboard/vendas/nova-venda', [VendasController::class, 'create'])->name('vendas.create');
    Route::post('/dashboard/vendas', [VendasController::class, 'store'])->name('vendas.store');
    Route::get('/vendas/{id}', [VendasController::class, 'show'])->name('vendas.show');
    Route::get('regiao', [RegiaoController::class, 'index'])->name('regiao.index');
    Route::post('regiao', [RegiaoController::class, 'store'])->name('regiao.store');
    Route::resource('empregados', EmpController::class);
    Route::resource('clientes', ClienteController::class);

});
Route::prefix('produtos')->name('produtos.')->group(function () {
    Route::get('/', [ProdutoController::class, 'index'])->name('index');
    Route::post('/', [ProdutoController::class, 'store'])->name('store');
    Route::get('/{id}', [ProdutoController::class, 'show'])->name('show');
    Route::put('/{id}', [ProdutoController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProdutoController::class, 'destroy'])->name('destroy');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('departamentos', DepartamentosController::class);
});

require __DIR__.'/auth.php';
