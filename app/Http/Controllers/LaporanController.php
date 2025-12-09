<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aset;
use App\Models\LaporanInventaris;
use App\Models\LaporanInventarisDetail;
use App\Exports\LaporanInventarisExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ruangan;
class LaporanController extends Controller
{
    /**
     * Halaman utama daftar laporan aset
     */
    public function indexInventaris()
    {
        // Menampilkan semua aset untuk dipilih
        $data = Aset::select('aset_id', 'nama', 'jenis', 'tanggal_input')
            ->orderBy('tanggal_input', 'desc')
            ->get();

        return view('admin.aset.v_laporan_aset', compact('data'));
    }

    /**
     * Halaman detail laporan + hasil pemutihan (dari model Aset)
     */
    public function detailInventaris($id)
    {
        $aset = Aset::findOrFail($id);

        // PEMUTIHAN SEKARANG MENGGUNAKAN MODEL ASET
        $pemutihan = $aset->cekPemutihan();

        return view('admin.aset.v_detail', compact('aset', 'pemutihan'));
    }

    /**
     * Export laporan ke Excel
     */
    public function exportInventaris($aset_id)
    {
        return Excel::download(
            new LaporanInventarisExport($aset_id),
            'laporan_aset_' . $aset_id . '.xlsx'
        );
    }

    /**
     * Simpan pemutihan manual ke tabel laporan_inventaris + detail
     */
    public function storePemutihan(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        // Validasi manual
        $request->validate([
            'catatan'       => 'nullable|string',
            'tanggal'       => 'required|date'
        ]);

        // Buat laporan periode hari ini jika belum ada
        $laporan = LaporanInventaris::create([
            'periode'        => date('Y-m'),
            'tanggal_dibuat' => now(),
        ]);

        // Simpan detail pemutihan
        LaporanInventarisDetail::create([
            'laporan_id'        => $laporan->laporan_id,
            'aset_id'           => $aset->aset_id,
            'kondisi'           => $aset->kondisi,
            'catatan'           => $request->catatan,
            'tanggal_pemutihan' => $request->tanggal
        ]);

        return back()->with('success', 'Pemutihan berhasil direkam.');
    }
    public function laporanAset(Request $request)
    {
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();
        $kondisiList = ['Baik', 'Rusak', 'Hilang', 'Dipinjam'];
        
        $query = Aset::with(['ruangan.kepalaLab.user']) // Muat relasi ruangan dan kepala lab
                      ->orderBy('aset_id', 'DESC');

        // Filter berdasarkan Ruangan (Revisi #4)
        if ($request->has('ruangan_id') && $request->ruangan_id != '') {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter berdasarkan Kondisi/Kerusakan (Revisi #3)
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }

        $aset = $query->get();

        return view('admin.aset.v_laporan_aset_ruangan', compact('aset', 'ruanganList', 'kondisiList'));
    }
}
