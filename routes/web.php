<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LaporanController;
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'proses'])->name('login.proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', fn() => view('admin.v_admin'));
    });

    Route::middleware('mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', fn() => view('mahasiswa.v_mahasiswa'));
    });

    Route::middleware('dosen')->group(function () {
        Route::get('/dosen/dashboard', fn() => view('dosen.v_dosen'));
    });

    Route::middleware('teknisi')->group(function () {
        Route::get('/teknisi/dashboard', fn() => view('teknisi.v_teknisi'));
    });

     Route::get('/admin/aset', [AsetController::class, 'index'])->name('aset.index');
    Route::get('/admin/aset/tambah', [AsetController::class, 'create'])->name('aset.tambah');
    Route::post('/admin/aset/tambah', [AsetController::class, 'store'])->name('aset.store');

    Route::get('/admin/aset/edit/{id}', [AsetController::class, 'edit'])->name('aset.edit');
    Route::post('/admin/aset/update/{id}', [AsetController::class, 'update'])->name('aset.update');

    Route::get('/admin/aset/laporan', [LaporanController::class, 'indexInventaris'])
    ->name('laporan.inventaris.index');

Route::get('/admin/aset/laporan/{id}', 
    [LaporanController::class, 'detailInventaris']
)->name('laporan.inventaris.show');

Route::get('/admin/aset/laporan/{id}/export', [LaporanController::class, 'exportInventaris'])
    ->name('laporan.inventaris.excel');
Route::post('/admin/aset/laporan/{id}/pemutihan',
    [LaporanController::class, 'storePemutihan']
)->name('laporan.inventaris.pemutihan.store');

     Route::delete('/hapus/{id}', [AsetController::class, 'destroy'])->name('aset.delete');
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
});
