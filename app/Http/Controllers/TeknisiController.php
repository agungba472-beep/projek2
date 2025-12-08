<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Komplain; // Pastikan ini di-import
use App\Models\Maintenance; // Pastikan ini di-import
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TeknisiController extends Controller
{
    public function index()
    {
        // 1. Mendapatkan ID Teknisi yang sedang login
        $teknisi_id = Auth::id();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 2. STATISTIK KOMPLAIN (Menggunakan assigned_to - sudah benar di tabel komplain)
        $komplainStats = [
            'totalAssigned' => Komplain::where('assigned_to', $teknisi_id)->count(),
            
            // Komplain yang perlu penanganan segera (Baru atau Diproses)
            'active' => Komplain::where('assigned_to', $teknisi_id)
                                 ->whereIn('status', ['baru', 'diproses'])
                                 ->count(),
                                
            // Komplain dengan SLA Warning: Belum Selesai & Deadline mendekat/terlewati
            'slaWarning' => Komplain::where('assigned_to', $teknisi_id)
                                     ->whereIn('status', ['baru', 'diproses'])
                                     ->where('sla_deadline', '<=', $tomorrow) 
                                     ->count(),
        ];

        // 3. STATISTIK MAINTENANCE (KOREKSI: Menggunakan teknisi_id)
        $maintenanceStats = [
            // KOREKSI: Mengganti 'assigned_to' menjadi 'teknisi_id'
            'totalAssigned' => Maintenance::where('teknisi_id', $teknisi_id)->count(), 
            
            // KOREKSI: Mengganti 'assigned_to' menjadi 'teknisi_id'
            'inProgress' => Maintenance::where('teknisi_id', $teknisi_id)
                                       ->where('status', 'Proses')
                                       ->count(),
                                       
            // KOREKSI: Mengganti 'assigned_to' menjadi 'teknisi_id'
            'scheduledToday' => Maintenance::where('teknisi_id', $teknisi_id)
                                           ->where('status', 'Terjadwal')
                                           ->whereDate('tanggal_dijadwalkan', $today)
                                           ->count(),
                                           
            // KOREKSI: Mengganti 'assigned_to' menjadi 'teknisi_id'
            'overdue' => Maintenance::where('teknisi_id', $teknisi_id)
                                    ->where('status', '!=', 'Selesai')
                                    ->whereDate('tanggal_dijadwalkan', '<', $today)
                                    ->count(),
        ];
        
        // 4. Data untuk Grafik
        $maintenanceSLAStatus = Maintenance::select('status', DB::raw('count(*) as total'))
                                             // KOREKSI: Mengganti 'assigned_to' menjadi 'teknisi_id'
                                             ->where('teknisi_id', $teknisi_id)
                                             ->groupBy('status')
                                             ->get();

        // 5. Mengirimkan SEMUA variabel ke view
        return view('teknisi.v_teknisi', compact('komplainStats', 'maintenanceStats', 'maintenanceSLAStatus'));
    }
}