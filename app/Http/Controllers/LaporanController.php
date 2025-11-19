<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\LaporanInventaris;
use App\Exports\LaporanInventarisExport;
use Maatwebsite\Excel\Facades\Excel;
class LaporanController extends Controller
{
    /**
     * Halaman utama daftar laporan aset
     * tampil seperti:
     * PC | Elektronik | 2025-02-02 | Lihat Laporan
     */
    public function indexInventaris()
    {
        // Ambil semua aset untuk ditampilkan sebagai “laporan”
        $data = Aset::select('aset_id', 'nama', 'jenis', 'tanggal_input')
            ->orderBy('tanggal_input', 'desc')
            ->get();

        return view('admin.aset.v_laporan_aset', compact('data'));
    }

    /**
     * Halaman detail laporan + pemutihan otomatis
     */
    public function detailInventaris($id)
    {
        // Ambil aset berdasarkan ID
        $aset = Aset::findOrFail($id);

        // Pemutihan otomatis dari function model
        $pemutihan = LaporanInventaris::cekPemutihan($aset);

        return view('admin.aset.v_detail', compact('aset', 'pemutihan'));
    }


    public function exportInventaris($aset_id)
{
    return Excel::download(
        new LaporanInventarisExport($aset_id),
        'laporan_aset_'.$aset_id.'.xlsx'
    );
}


    /**
     * Tambah data pemutihan baru untuk aset tertentu
     */
  
}
