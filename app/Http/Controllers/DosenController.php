<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan; // Model Ruangan
use App\Models\PeminjamanFasilitas; // Model Fasilitas
use App\Models\Aset; // Model untuk mencari Ruangan Kelas
use App\Models\Komplain; // Model Komplain
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // Definisikan rentang waktu untuk jadwal (Kemarin hingga Besok)
        $yesterday = Carbon::yesterday();
        $tomorrow = Carbon::tomorrow();

        // 1. STATISTIK PEMINJAMAN DAN KOMPLAIN
        $totalRuangan = PeminjamanRuangan::where('user_id', $user_id)->count();
        $totalFasilitas = PeminjamanFasilitas::where('user_id', $user_id)->count();
        
        $stats = [
            'totalPengajuan' => $totalRuangan + $totalFasilitas,
            'aktif'          => PeminjamanRuangan::where('user_id', $user_id)->whereIn('status', ['disetujui', 'dipinjam'])->count() +
                                PeminjamanFasilitas::where('user_id', $user_id)->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'fasilitasAktif' => PeminjamanFasilitas::where('user_id', $user_id)->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'totalKomplain'  => Komplain::where('user_id', $user_id)->count(),
            'komplainProses' => Komplain::where('user_id', $user_id)->whereIn('status', ['baru', 'diproses'])->count(),
        ];

        // 2. RUANGAN KELAS YANG SEDANG SIBUK (KEMARIN, HARI INI, BESOK)
        
        // Ambil semua data peminjaman yang statusnya aktif dan tanggalnya masuk dalam rentang Kemarin s/d Besok.
        $ruanganSibuk = PeminjamanRuangan::whereIn('status', ['disetujui', 'dipinjam'])
                                        ->whereDate('jam_kembali', '>=', $yesterday) // Belum selesai sebelum kemarin
                                        ->whereDate('jam_pinjam', '<=', $tomorrow) // Mulai sebelum besok
                                        ->orderBy('jam_pinjam', 'asc')
                                        ->get();
                               
        // Kita tidak perlu Aset::where('jenis', 'Ruangan Kelas') lagi karena kita fokus pada data peminjaman.
                               
        return view('dosen.v_dosen', compact('stats', 'ruanganSibuk')); // Ganti nama variabel menjadi ruanganSibuk
    }
}