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
use App\Models\Ruangan; // Model Ruangan
use App\Models\Dosen;   // Model Dosen

class DosenController extends Controller
{
    public function index()
{
    $user = Auth::user();
    // Dosen ID hanya tersedia jika user sudah melengkapi profil (ada entry di tabel dosen)
    $dosen = Dosen::where('user_id', $user->user_id)->first();
    $dosenId = $dosen ? $dosen->dosen_id : null;
    
    // 1. Ruangan yang diampu oleh Dosen ini
    $ruanganDiampu = Ruangan::where('kepala_lab_id', $dosenId)->pluck('ruangan_id');
    
    // 2. Aset Rusak di Ruangan yang dia ampu
    $asetRusakDiampu = Aset::whereIn('ruangan_id', $ruanganDiampu)
                           ->where('kondisi', 'Rusak')
                           ->count();
    
    // 3. Total Komplain yang sedang diproses (contoh)
    $komplainProses = Komplain::where('status', 'menunggu')->count();

    $stats = [
        'asetRusakDiampu' => $asetRusakDiampu,
        'jumlahRuanganDiampu' => $ruanganDiampu->count(),
        'totalKomplainMenunggu' => $komplainProses,
    ];
    
    return view('dosen.v_dosen', compact('stats'));
}
    public function asetIndex()
{
    // 1. Dapatkan user ID yang sedang login
    $user = Auth::user();

    // 2. Dapatkan ID Dosen dari tabel 'dosen'
    // Perlu dimuat relasi Dosen karena FK Ruangan menunjuk ke Dosen ID, bukan User ID
    $dosen = Dosen::where('user_id', $user->user_id)->first();
    $dosenId = $dosen ? $dosen->dosen_id : null;

    // Inisialisasi query Aset dengan eager loading
    $query = Aset::with(['ruangan.kepalaLab'])
                  ->orderBy('aset_id', 'DESC');

    if ($dosenId) {
        // 3. Dapatkan ID ruangan yang diampu oleh Dosen ini
        $ruanganDiampuIds = Ruangan::where('kepala_lab_id', $dosenId)->pluck('ruangan_id');

        // 4. Filter Aset: Hanya tampilkan aset yang berada di ruangan yang diampu
        $query->whereIn('ruangan_id', $ruanganDiampuIds);
        
        // 5. Dukungan Filter Kondisi (jika ada tautan dari dashboard)
        if (request()->has('kondisi') && request('kondisi') != '') {
            $query->where('kondisi', request('kondisi'));
        }
        
    } else {
        // Jika Dosen belum melengkapi profil (belum ada dosenId), tampilkan aset kosong.
        $query->where('aset_id', null); 
    }

    $aset = $query->get();

    return view('dosen.v_aset_index', compact('aset'));
}
}