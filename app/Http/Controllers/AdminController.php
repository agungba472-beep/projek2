<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanFasilitas;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\User;
use App\Models\Maintenance;
use App\Models\Komplain; // Import Model Komplain
use Carbon\Carbon;
use App\Models\LaporanKerusakan;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Logika untuk menghitung aset rusak bulan ini (Revisi #1)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Hitung jumlah laporan kerusakan (new reports) yang dibuat di bulan ini
        $laporan_rusak_bulan_ini = LaporanKerusakan::whereBetween('tanggal_lapor', [$startOfMonth, $endOfMonth])
                                                ->count();
        // === 1. STATISTIK UTAMA ===
        $stats = [
            'totalAset' => Aset::count(),
            'totalPengguna' => User::count(),
            'pengajuanMenunggu' => PeminjamanFasilitas::where('status', 'menunggu')->count(),
            'maintenanceTerjadwal' => Maintenance::where('status', 'Terjadwal')->count(),
            
            // STATS BARU: Komplain
            'totalKomplain' => Komplain::count(),
            'komplainAktif' => Komplain::whereIn('status', ['baru', 'diproses'])->count(),
        ];
        
        // === 2. DATA UNTUK GRAFIK ===
        
        // A. Grafik Aset Kondisi
        $asetKondisi = Aset::select('kondisi', DB::raw('count(*) as total'))
                            ->groupBy('kondisi')
                            ->get();

        // E. Grafik Status Maintenance
        $maintenanceStatus = Maintenance::select('status', DB::raw('count(*) as total'))
                                        ->groupBy('status')
                                        ->get();

        // D. Grafik Tren Pengajuan (6 bulan terakhir)
        $pengajuanTren = PeminjamanRuangan::select(
                                    DB::raw('MONTH(created_at) as bulan'),
                                    DB::raw('YEAR(created_at) as tahun'),
                                    DB::raw('count(*) as total')
                                )
                                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                                ->groupBy('tahun', 'bulan')
                                ->orderBy('tahun', 'asc')
                                ->orderBy('bulan', 'asc')
                                ->get();

        // F. GRAFIK BARU: Komplain Berdasarkan Level SLA (Major vs Minor)
        $komplainSLAStatus = Komplain::select('level_teknisi', DB::raw('count(*) as total'))
                                        ->whereNotNull('level_teknisi')
                                        ->groupBy('level_teknisi')
                                        ->get();

        // C. Riwayat Pengguna Aktif
        $riwayatUsers = User::orderBy('last_seen', 'desc')->take(5)->get();
        // Anda harus memastikan kolom last_seen terupdate saat login/activity
        $usersOnline = User::where('last_seen', '>=', Carbon::now()->subMinutes(5))->get();

        return view('admin.v_admin', compact(
            'stats',
            'asetKondisi',
            'maintenanceStatus',
            'pengajuanTren',
            'komplainSLAStatus', // Data baru
            'riwayatUsers',
            'usersOnline',
            'laporan_rusak_bulan_ini'
        ));
    }
}