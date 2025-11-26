<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $asetList = Aset::all();
        $notifikasi = [];

        foreach ($asetList as $aset) {
            $tanggalPeroleh = Carbon::parse($aset->tanggal_peroleh);
            $tanggalKadaluarsa = $tanggalPeroleh->copy()->addYears($aset->umur_maksimal);
            $hariTersisa = now()->diffInDays($tanggalKadaluarsa, false);

            // Notifikasi berdasarkan umur
            if ($hariTersisa < 0) {
                $notifikasi[] = [
                    'aset' => $aset,
                    'pesan' => 'Masa penggunaan telah berakhir',
                    'aksi' => 'Kadaluarsa'
                ];
            } elseif ($hariTersisa <= 180) {
                $notifikasi[] = [
                    'aset' => $aset,
                    'pesan' => 'Masa penggunaan akan berakhir',
                    'aksi' => 'Hampir Berakhir'
                ];
            }

            // Notifikasi kondisi rusak
            if (strtolower($aset->kondisi) === 'rusak') {
                $notifikasi[] = [
                    'aset' => $aset,
                    'pesan' => 'Aset rusak dan perlu pemutihan',
                    'aksi' => 'Proses Pemutihan'
                ];
            }
        }

        return view('admin.aset.v_notifikasi_aset', compact('notifikasi'));

    }
}
