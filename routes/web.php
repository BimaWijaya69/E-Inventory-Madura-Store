<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TransaksiMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginpost');
});


Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // material
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials');
    Route::get('/materials/{id}', [MaterialController::class, 'edit']);
    Route::get('/material/generate-kode', [MaterialController::class, 'generateKode']);
    Route::post('/materials', [MaterialController::class, 'store'])->name('material-post');
    Route::post('/materials/update/{id}', [MaterialController::class, 'update'])->name('material-update');
    Route::delete('/materials/delete/{id}', [MaterialController::class, 'destroy'])->name('material-delete');

    Route::get('/manajmen-users', [UserController::class, 'index'])->name('users');

    Route::post('/transaksi', [TransaksiMaterialController::class, 'store'])->name('transaksi');
    Route::put('/transaksi/{id}', [TransaksiMaterialController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [TransaksiMaterialController::class, 'destroy'])->name('transaksi.delete');

    Route::get('/material-masuk', [TransaksiMaterialController::class, 'materialMasukView'])->name('material-masuks');

    Route::get('/material-keluar', [TransaksiMaterialController::class, 'materialKeluarView'])->name('material-keluars');
    Route::get('/material-keluar/create', [TransaksiMaterialController::class, 'createMaterialKeluarView'])->name('create-material-keluars');
    Route::get('/material-keluar/{id}/update', [TransaksiMaterialController::class, 'editMaterialKeluarView'])->name('edit-material-keluars');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
