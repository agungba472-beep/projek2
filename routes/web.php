<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController;
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'proses'])->name('login.proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', fn() => view('v_admin'));
    });

    Route::middleware('mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', fn() => view('v_mahasiswa'));
    });

    Route::middleware('dosen')->group(function () {
        Route::get('/dosen/dashboard', fn() => view('v_dosen'));
    });

    Route::middleware('teknisi')->group(function () {
        Route::get('/teknisi/dashboard', fn() => view('v_teknisi'));
    });

    Route::get('/profile', [ProfilController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
});
