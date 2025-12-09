<?php

namespace App\Http\Controllers;

use App\Models\Ruangan; 
use App\Models\Dosen;   // Diperlukan untuk dropdown Kepala Lab
use App\Models\Aset;   // Diperlukan untuk cek Foreign Key sebelum dihapus
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RuanganController extends Controller
{
    /**
     * Tampilkan daftar ruangan (READ - Index)
     */
    public function index()
    {
        // Muat relasi kepalaLab (Dosen)
        $ruangan = Ruangan::with('kepalaLab.user')->get(); 
        // View yang dibutuhkan: resources/views/admin/ruangan/v_ruangan_index.blade.php
        return view('admin.ruangan.v_ruangan_index', compact('ruangan'));
    }

    /**
     * Tampilkan form tambah ruangan (CREATE)
     */
    public function create()
    {
        // Ambil semua Dosen untuk dropdown Kepala Lab
        $dosen = Dosen::all(); 
        // View yang dibutuhkan: resources/views/admin/ruangan/v_tambah_ruangan.blade.php
        return view('admin.ruangan.v_tambah_ruangan', compact('dosen'));
    }

    /**
     * Simpan ruangan baru (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:ruangan,nama_ruangan',
            'kepala_lab_id' => 'nullable|exists:dosen,dosen_id',
        ]);

        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kepala_lab_id' => $request->kepala_lab_id,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }
    
    // ===============================================
    // FUNGSI CRUD LANJUTAN (FIN 1)
    // ===============================================

    /**
     * Tampilkan form edit ruangan (EDIT)
     */
    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $dosen = Dosen::all();
        // View yang dibutuhkan: resources/views/admin/ruangan/v_update_ruangan.blade.php
        return view('admin.ruangan.v_update_ruangan', compact('ruangan', 'dosen'));
    }

    /**
     * Update ruangan (UPDATE)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // Pastikan unique diabaikan untuk ruangan yang sedang diedit
            'nama_ruangan' => 'required|string|max:100|unique:ruangan,nama_ruangan,'.$id.',ruangan_id',
            'kepala_lab_id' => 'nullable|exists:dosen,dosen_id',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'kepala_lab_id' => $request->kepala_lab_id,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    /**
     * Hapus ruangan (DESTROY)
     */
    public function destroy($id)
    {
        try {
            $ruangan = Ruangan::findOrFail($id);
            
            // Cek apakah ada aset yang masih terhubung (sesuai FK ON DELETE RESTRICT)
            $jumlahAset = Aset::where('ruangan_id', $id)->count();
            
            if ($jumlahAset > 0) {
                return redirect()->route('ruangan.index')->with('error', 
                    "Gagal menghapus ruangan: Masih ada {$jumlahAset} aset yang terhubung ke ruangan ini."
                );
            }

            $ruangan->delete();
            return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
        
        } catch (\Exception $e) {
            // Tangani error lain, misal jika FK tidak dicek secara manual
            return redirect()->route('ruangan.index')->with('error', 'Terjadi kesalahan saat menghapus ruangan.');
        }
    }
}