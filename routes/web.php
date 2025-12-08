<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController; 
use App\Http\Controllers\KelolaPenggunaController;
use App\Http\Middleware\UserActivity;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKomplainController;
use App\Http\Controllers\TKomplainController;
use App\Http\Controllers\TeknisiController;
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'proses'])->name('login.proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth','user.activity'])->group(function () {

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware('mahasiswa')->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.v_mahasiswa');
    });

    Route::middleware('dosen')->group(function () {
    Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.v_dosen');
    });

    Route::middleware('teknisi')->group(function () {
    Route::get('/teknisi/dashboard', [TeknisiController::class, 'index'])->name('teknisi.v_teknisi');
    });

     Route::get('/admin/aset', [AsetController::class, 'index'])->name('aset.index');
    Route::get('/admin/aset/tambah', [AsetController::class, 'create'])->name('aset.tambah');
    Route::post('/admin/aset/tambah', [AsetController::class, 'store'])->name('aset.store');

    Route::get('/admin/aset/edit/{id}', [AsetController::class, 'edit'])->name('aset.edit');
    Route::post('/admin/aset/update/{id}', [AsetController::class, 'update'])->name('aset.update');
    Route::get('/aset/{id}/qr/download', [AsetController::class, 'downloadQR'])
    ->name('qr.download');



    Route::get('/admin/aset/laporan', [LaporanController::class, 'indexInventaris'])
    ->name('laporan.inventaris.index');

Route::get('/admin/aset/laporan/{id}', 
    [LaporanController::class, 'detailInventaris']
)->name('laporan.inventaris.show');

Route::get('/admin/aset/laporan/{id}/export', [LaporanController::class, 'exportInventaris'])
    ->name('laporan.inventaris.export');
Route::post('/laporan/pemutihan/{id}', [LaporanController::class, 'storePemutihan'])
    ->name('laporan.storePemutihan');

    Route::get('/admin/aset/notifikasi_aset', [NotifikasiController::class, 'index'])
     ->name('notifikasi.aset');

     Route::prefix('admin/manajemen/kelola')->group(function () {
    Route::get('/', [KelolaPenggunaController::class, 'index'])->name('pengguna.index');

    Route::post('/store', [KelolaPenggunaController::class, 'store'])->name('pengguna.store');

    Route::put('/update/{user_id}', [KelolaPenggunaController::class, 'update'])->name('pengguna.update');

    Route::delete('/delete/{user_id}', [KelolaPenggunaController::class, 'destroy'])->name('pengguna.delete');
});
Route::middleware('admin')->group(function () {
    // 1. Menampilkan semua pengajuan yang statusnya 'menunggu'
    Route::get('/admin/pengajuan', [PeminjamController::class, 'indexPengajuan'])->name('admin.pengajuan.index');

    // 2. Aksi Konfirmasi Ruangan
    Route::post('/admin/pengajuan/ruangan/setujui/{id}', [PeminjamController::class, 'setujuiRuangan'])->name('admin.pengajuan.ruangan.setujui');
    Route::post('/admin/pengajuan/ruangan/tolak/{id}', [PeminjamController::class, 'tolakRuangan'])->name('admin.pengajuan.ruangan.tolak');
    
    // 3. Aksi Konfirmasi Fasilitas
    Route::post('/admin/pengajuan/fasilitas/setujui/{id}', [PeminjamController::class, 'setujuiFasilitas'])->name('admin.pengajuan.fasilitas.setujui');
    Route::post('/admin/pengajuan/fasilitas/tolak/{id}', [PeminjamController::class, 'tolakFasilitas'])->name('admin.pengajuan.fasilitas.tolak');
});
    Route::get('/admin/peminjaman/ruangan/pengajuan', [PeminjamController::class, 'ruanganLogIndex'])
        ->name('admin.pengajuan.ruangan.index');

    // Contoh Route DELETE di file web.php Anda
Route::delete('/admin/peminjaman/ruangan/force-delete/{id}', [PeminjamController::class, 'hapusAdminRuangan'])->name('admin.ruangan.force.delete');
Route::delete('/admin/peminjaman/fasilitas/force-delete/{id}', [PeminjamController::class, 'hapusAdminRuangan'])->name('admin.fasilitas.force.delete');


// ADMIN Komplain
Route::prefix('admin/komplain')->group(function () {
    Route::get('/', [AdminKomplainController::class, 'index'])->name('admin.komplain.index');
    Route::get('/detail/{id}', [AdminKomplainController::class, 'show'])->name('admin.komplain.show');
   Route::put('/admin/komplain/status/{id}', [AdminKomplainController::class, 'update'])->name('admin.komplain.update');
    Route::delete('/hapus/{id}', [AdminKomplainController::class, 'destroy'])->name('admin.komplain.destroy');
    Route::get('/admin/komplain/proses/{id}', [AdminKomplainController::class, 'proses'])->name('admin.komplain.proses');
Route::post('/admin/komplain/assign/{id}', [AdminKomplainController::class, 'assign'])->name('admin.komplain.assign');
Route::get('admin/komplain/riwayat', [AdminKomplainController::class, 'riwayat'])->name('admin.komplain.riwayat');

});

// =============================
// MAHASISWA
// =============================
Route::middleware(['auth', 'mahasiswa'])->group(function () {

    // Dashboard
    // Route::get('/mahasiswa/dashboard', fn() => view('mahasiswa.v_mahasiswa'))->name('mahasiswa.dashboard');

    // --- RUANGAN (PREFIX: /mahasiswa/peminjaman/ruangan) ---
    // Index: Menampilkan daftar peminjaman ruangan user
    Route::get('/mahasiswa/peminjaman/ruangan', [PeminjamController::class, 'ruanganIndex'])
        ->name('pinjam.ruangan.index'); // PERBAIKAN NAMA ROUTE
    // Store: Menyimpan pengajuan ruangan
    Route::post('/mahasiswa/peminjaman/ruangan/store', [PeminjamController::class, 'ruanganStore'])
        ->name('pinjam.ruangan.store');
    // Kembalikan: Aksi pengembalian ruangan
    Route::post('/mahasiswa/peminjaman/ruangan/kembalikan/{id}', [PeminjamController::class, 'kembalikanRuangan'])
        ->name('pinjam.ruangan.kembalikan');
    // Batalkan/Hapus: Menghapus peminjaman ruangan (Batalkan)
    Route::delete('/mahasiswa/peminjaman/ruangan/delete', [PeminjamController::class, 'hapusRuangan'])
        ->name('pinjam.ruangan.delete');


    // --- FASILITAS (PREFIX: /mahasiswa/peminjaman/fasilitas) ---
    // Index: Menampilkan daftar peminjaman fasilitas user
    Route::get('/mahasiswa/peminjaman/fasilitas', [PeminjamController::class, 'fasilitasIndex'])
        ->name('pinjam.fasilitas.index'); // PERBAIKAN NAMA ROUTE
    
    // Store: Menyimpan pengajuan fasilitas baru (Aksi Pinjam)
    Route::post('/mahasiswa/peminjaman/fasilitas/store', [PeminjamController::class, 'fasilitasStore'])
        ->name('pinjam.fasilitas.store');
        
    // Kembalikan: Aksi pengembalian fasilitas
    Route::post('/mahasiswa/peminjaman/fasilitas/kembalikan/{id}', [PeminjamController::class, 'kembalikanFasilitas'])
        ->name('pinjam.fasilitas.kembalikan'); 
        
    // Batalkan: Aksi pembatalan peminjaman fasilitas
    Route::delete('/mahasiswa/peminjaman/fasilitas/batalkan/{id}', [PeminjamController::class, 'batalkanFasilitas'])
        ->name('pinjam.fasilitas.batalkan');


    // --- Komplain ---
    Route::get('/mahasiswa/peminjaman/komplain', [KomplainController::class, 'index'])
        ->name('komplain.index');
    Route::post('/mahasiswa/peminjaman/komplain/store', [KomplainController::class, 'store'])
        ->name('komplain.store');
        
});

// =============================
// DOSEN
// =============================
Route::middleware(['auth', 'dosen'])->group(function () {

    // Dashboard
    // Route::get('/dosen/dashboard', fn() => view('dosen.v_dosen'))->name('dosen.dashboard');

    // --- RUANGAN ---
    Route::get('/dosen/peminjaman/ruangan', [PeminjamController::class, 'ruanganIndex'])
        ->name('dosen.pinjam.ruangan.index'); 
    Route::post('/dosen/peminjaman/ruangan/store', [PeminjamController::class, 'ruanganStore'])
        ->name('dosen.pinjam.ruangan.store');
    Route::delete('/dosen/peminjaman/ruangan/delete', [PeminjamController::class, 'hapusRuangan'])
        ->name('dosen.pinjam.ruangan.delete'); 
    Route::post('/dosen/peminjaman/ruangan/kembalikan/{id}', [PeminjamController::class, 'kembalikanRuangan'])
        ->name('dosen.pinjam.ruangan.kembalikan'); 

    // --- FASILITAS ---
    Route::get('/dosen/peminjaman/fasilitas', [PeminjamController::class, 'fasilitasIndex'])
        ->name('dosen.pinjam.fasilitas.index'); 

    // AKSI STORE (PENTING untuk Modal Pinjam Sekarang)
    Route::post('/dosen/peminjaman/fasilitas/store', [PeminjamController::class, 'fasilitasStore'])
        ->name('dosen.pinjam.fasilitas.store');

    // AKSI LAIN
    Route::post('/dosen/peminjaman/fasilitas/kembalikan/{id}', [PeminjamController::class, 'kembalikanFasilitas'])
        ->name('dosen.pinjam.fasilitas.kembalikan'); 
    Route::delete('/dosen/peminjaman/fasilitas/batalkan/{id}', [PeminjamController::class, 'batalkanFasilitas'])
        ->name('dosen.pinjam.fasilitas.batalkan');

    // --- Komplain ---
    Route::get('/dosen/peminjaman/komplain', [KomplainController::class, 'index'])
        ->name('dosen.komplain.index');
    Route::post('/dosen/peminjaman/komplain/store', [KomplainController::class, 'store'])
        ->name('dosen.komplain.store');
});

     Route::delete('/admin/aset/hapus/{id}', [AsetController::class, 'destroy'])->name('aset.delete');
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
});

Route::controller(MaintenanceController::class)->group(function () {
// TEKNISI

    Route::get('/teknisi/maintenance', 'index')->name('maintenance.index');
    Route::post('/teknisi/maintenance/store', 'store')->name('maintenance.store');
    Route::post('/teknisi/maintenance/update/{id}', 'update')->name('maintenance.update');
Route::delete('/teknisi/maintenance/delete/{id}', 'delete')->name('maintenance.delete');

    Route::get('/teknisi/maintenance/detail/{id}', 'show')->name('maintenance.detail');
    Route::post('/teknisi/maintenance/detail/store', 'storeDetail')->name('maintenance.detail.store');

    Route::get('/maintenance/aset/{id}', [MaintenanceController::class, 'listByAset'])
    ->name('maintenance.listAset');

Route::get('/maintenance/detail/{id}', [MaintenanceController::class, 'detail'])
    ->name('maintenance.detail');

Route::get('/maintenance/{aset_id}', [MaintenanceController::class, 'listByAset'])
    ->name('maintenance.show');
});
Route::get('/admin/aset/{id}/maintenance', 
    [MaintenanceController::class, 'listByAset']
)->name('maintenance.listByAset');

Route::post('/admin/maintenance/store',
    [MaintenanceController::class, 'store']
)->name('maintenance.store');

Route::post('/admin/maintenance/update/{id}',
    [MaintenanceController::class, 'update']
)->name('maintenance.update');

Route::delete('/maintenance/delete/{id}', [MaintenanceController::class, 'delete'])
    ->name('maintenance.delete');


Route::get('/admin/maintenance/detail/{id}',
    [MaintenanceController::class, 'detail']
)->name('maintenance.detail');

    Route::prefix('teknisi')->middleware('auth')->group(function () {
    Route::get('/komplain', [TKomplainController::class, 'index'])->name('teknisi.komplain.index');
    Route::get('/komplain/{id}', [TKomplainController::class, 'show'])->name('teknisi.komplain.show');
    Route::put('/komplain/{id}', [TKomplainController::class, 'update'])->name('teknisi.komplain.update');
});
