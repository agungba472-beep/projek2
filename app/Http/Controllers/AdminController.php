<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Aset;
use App\Models\Maintenance;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanFasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Digunakan untuk agregasi data

class AdminController extends Controller
{
    public function index()
    {
        // 1. DATA RINGKASAN (CARD STATS)
        $totalAset = Aset::count();
        $totalPengguna = User::count();
        $pengajuanMenunggu = PeminjamanRuangan::where('status', 'menunggu')->count() +
                             PeminjamanFasilitas::where('status', 'menunggu')->count();
        $maintenanceTerjadwal = Maintenance::where('status', 'Terjadwal')->count();

        // 2. DATA GRAFIK ASET BERDASARKAN KONDISI
        $asetKondisi = Aset::select('kondisi', DB::raw('count(*) as total'))
                            ->groupBy('kondisi')
                            ->get();

        // 3. DATA GRAFIK MAINTENANCE STATUS
        $maintenanceStatus = Maintenance::select('status', DB::raw('count(*) as total'))
                                        ->groupBy('status')
                                        ->get();

        // 4. RIWAYAT ONLINE USER (User Activity)
        // Kriteria 'Online': last_seen dalam 2 menit terakhir dan status bukan 'nonaktif'
        $usersOnline = User::where('last_seen', '>=', Carbon::now()->subMinutes(2))
                            ->where('status', '!=', 'nonaktif')
                            ->orderBy('last_seen', 'DESC')
                            ->get();
        
        // Riwayat user terakhir yang aktif (terlepas dari status online/offline)
        $riwayatUsers = User::orderBy('last_seen', 'DESC')
                             ->limit(5)
                             ->get();


        // 5. RIWAYAT PENGAJUAN (Grafik Tren Bulanan)
        $pengajuanTren = PeminjamanRuangan::select(
                                DB::raw('MONTH(jam_pinjam) as bulan'),
                                DB::raw('YEAR(jam_pinjam) as tahun'),
                                DB::raw('count(*) as total')
                            )
                            ->groupBy('tahun', 'bulan')
                            ->orderBy('tahun', 'DESC')
                            ->orderBy('bulan', 'DESC')
                            ->limit(6)
                            ->get();


        $data = [
            // Ringkasan
            'stats' => compact('totalAset', 'totalPengguna', 'pengajuanMenunggu', 'maintenanceTerjadwal'),
            
            // Grafik
            'asetKondisi' => $asetKondisi,
            'maintenanceStatus' => $maintenanceStatus,
            
            // Riwayat
            'usersOnline' => $usersOnline,
            'riwayatUsers' => $riwayatUsers,
            
            // Tren
            'pengajuanTren' => $pengajuanTren,
        ];

        return view('admin.v_admin', $data);
    }
}