<?php

use App\Exports\TransaksiMaterialExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TransaksiMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginpost');
});


Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/material/generate-kode', [MaterialController::class, 'generateKode']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // material
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials');
    Route::get('/materials/{id}', [MaterialController::class, 'edit']);
    Route::post('/materials', [MaterialController::class, 'store'])->name('material-post');
    Route::post('/materials/update/{id}', [MaterialController::class, 'update'])->name('material-update');
    Route::delete('/materials/delete/{id}', [MaterialController::class, 'destroy'])->name('material-delete');

    Route::get('/manajemen-users', [UserController::class, 'index'])->name('users');
    Route::post('/manajemen-users', [UserController::class, 'store'])->name('users.store');
    Route::get('/manajemen-users/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/manajemen-users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/manajemen-users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/manajemen-users/is_active/{id}', [UserController::class, 'is_active'])->name('users.is_active');

    Route::post('/transaksi', [TransaksiMaterialController::class, 'store'])->name('transaksi');
    Route::put('/transaksi/{id}', [TransaksiMaterialController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [TransaksiMaterialController::class, 'destroy'])->name('transaksi.delete');
    Route::get('/transaksi/{id}/detail', [TransaksiMaterialController::class, 'detailTransaksi'])->name('transaksi.detail');
    Route::get('/transaksi/{id}/confirm', [TransaksiMaterialController::class, 'confirm'])->name('transaksi.confirm');
    Route::get('/transaksi/{id}/ajuan-kembali', [TransaksiMaterialController::class, 'ajuanKembali'])->name('transaksi.ajuan-kembali');
    Route::post('/transaksi/{id}/decline', [TransaksiMaterialController::class, 'decline'])->name('transaksi.decline');

    Route::get('/material-masuk', [TransaksiMaterialController::class, 'materialMasukView'])->name('material-masuks');
    Route::get('/material-masuk/create', [TransaksiMaterialController::class, 'createMaterialMasukView'])->name('material-masuks.create');
    Route::get('/material-masuk/{id}/update', [TransaksiMaterialController::class, 'editMaterialMasukView'])->name('material-masuks.edit');
    Route::get('/material-masuk/{id}/detail', [TransaksiMaterialController::class, 'detailTransaksi'])->name('material.detail-masuk');


    Route::get('/material-keluar', [TransaksiMaterialController::class, 'materialKeluarView'])->name('material-keluars');
    Route::get('/material-keluar/create', [TransaksiMaterialController::class, 'createMaterialKeluarView'])->name('create-material-keluars');
    Route::get('/material-keluar/{id}/update', [TransaksiMaterialController::class, 'editMaterialKeluarView'])->name('edit-material-keluars');
    Route::get('/material-keluar/{id}/detail', [TransaksiMaterialController::class, 'detailTransaksi'])->name('material.detail-keluar');

    Route::get('/export-transaksi', [TransaksiMaterialController::class, 'export'])
        ->name('export.transaksi');

    Route::get('/export-transaksi/{jenis}', [TransaksiMaterialController::class, 'export']);
    Route::get('/transaksi/print/{id}', [TransaksiMaterialController::class, 'print'])->name('transaksi.print');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
