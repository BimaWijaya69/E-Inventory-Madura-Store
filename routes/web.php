<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TransaksiMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsLogin;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginpost');
});


Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials');
    Route::get('/manajmen-users', [UserController::class, 'index'])->name('users');
    //Transaksi
    Route::get('/material-masuk', [TransaksiMaterialController::class, 'penerimaanView'])->name('material-masuks');
    Route::get('/material-masuk/create', [TransaksiMaterialController::class, 'createPenerimaan'])->name('material-masuks.create');
    Route::get('/material-keluar', [TransaksiMaterialController::class, 'pengeluaranView'])->name('material-keluars');
    Route::get('/material-keluar/create', [TransaksiMaterialController::class, 'createPengeluaran'])->name('material-keluars.create');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
