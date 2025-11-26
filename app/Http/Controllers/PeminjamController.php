<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanFasilitas;
use App\Models\PeminjamanFasilitasDetail;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    /* ============================
       PEMINJAMAN RUANGAN
    ============================ */

    public function ruanganIndex()
{
    $role = Auth::user()->role;

    $data = PeminjamanRuangan::orderBy('peminjaman_ruangan_id', 'DESC')->get();

    if ($role === 'Mahasiswa') {
        return view('mahasiswa.peminjaman.v_ruangan', compact('data'));
    }

    if ($role === 'Dosen') {
        return view('dosen.peminjaman.v_ruangan', compact('data'));
    }

    abort(403, 'Anda tidak memiliki akses.');
}


    public function ruanganStore(Request $request)
    {
        $request->validate([
            'nama_ruangan'     => 'required',
            'nama_peminjam'  => 'required',
            'mata_kuliah'    => 'required',
            'dosen_pengampu' => 'required',
            'jam_pinjam'     => 'required',
            'jam_kembali'    => 'required',
        ]);

        PeminjamanRuangan::create([
            'user_id'        => Auth::id(),
            'nama_ruangan'     => $request->nama_ruangan,
            'nama_peminjam'  => $request->nama_peminjam,
            'mata_kuliah'    => $request->mata_kuliah,
            'dosen_pengampu' => $request->dosen_pengampu,
            'jam_pinjam'     => $request->jam_pinjam,
            'jam_kembali'    => $request->jam_kembali,
            'status'         => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil diajukan');
    }



    /* ============================
       PEMINJAMAN FASILITAS
    ============================ */

    public function fasilitasIndex()
{
    $role = Auth::user()->role;

    $data = PeminjamanFasilitas::with('detail')
                ->orderBy('peminjaman_fasilitas_id', 'DESC')
                ->get();

    if ($role === 'Mahasiswa') {
        return view('mahasiswa.peminjaman.v_fasilitas', compact('data'));
    }

    if ($role === 'Dosen') {
        return view('dosen.peminjaman.v_fasilitas', compact('data'));
    }

    abort(403, 'Anda tidak memiliki akses.');
}


    public function fasilitasStore(Request $request)
{
    $request->validate([
        'nama_peminjam' => 'required',
        'jam_pinjam'    => 'required',
        'jam_kembali'   => 'required',
        'fasilitas'     => 'required|array',
        'jumlah'        => 'required|array'
    ]);

    // Insert ke tabel utama
    $fasilitas = PeminjamanFasilitas::create([
        'user_id'        => Auth::id(),
        'nama_peminjam'  => $request->nama_peminjam,
        'jam_pinjam'     => $request->jam_pinjam,
        'jam_kembali'    => $request->jam_kembali,
        'status'         => 'menunggu'
    ]);

    // --- FIXED ---
    // memastikan index jumlah sesuai index fasilitas
    foreach ($request->fasilitas as $index => $namaFasilitas) {

        $jumlah = $request->jumlah[$index] ?? 1; // default 1 jika tidak diisi

        PeminjamanFasilitasDetail::create([
            'peminjaman_fasilitas_id' => $fasilitas->peminjaman_fasilitas_id,
            'nama_fasilitas'          => $namaFasilitas,
            'jumlah'                  => $jumlah
        ]);
    }

    return redirect()->back()->with('success', 'Peminjaman fasilitas berhasil diajukan');
}




    /* ============================
       HAPUS DATA
    ============================ */

    public function hapusRuangan($id)
    {
        PeminjamanRuangan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil dihapus');
    }

    public function hapusFasilitas($id)
    {
        // hapus child dulu
        PeminjamanFasilitasDetail::where('peminjaman_fasilitas_id', $id)->delete();
        PeminjamanFasilitas::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Peminjaman fasilitas berhasil dihapus');
    }
    public function indexPengajuan()
{
    // Ambil semua pengajuan ruangan yang 'menunggu'
    $ruangan_pengajuan = PeminjamanRuangan::where('status', 'menunggu')
                            ->orderBy('jam_pinjam', 'ASC')
                            ->get();

    // Ambil semua pengajuan fasilitas yang 'menunggu' dan muat relasi detail()
    $fasilitas_pengajuan = PeminjamanFasilitas::with('detail')
                            ->where('status', 'menunggu')
                            ->orderBy('jam_pinjam', 'ASC')
                            ->get();
                            

    return view('admin.fasilitas.v_pengajuan', compact('ruangan_pengajuan', 'fasilitas_pengajuan'));
}

// --- RUANGAN ACTIONS ---

public function setujuiRuangan($id)
{
    $peminjaman = PeminjamanRuangan::findOrFail($id);
    $peminjaman->update(['status' => 'disetujui']);

    return back()->with('success', 'Peminjaman ruangan berhasil disetujui.');
}

public function tolakRuangan($id)
{
    $peminjaman = PeminjamanRuangan::findOrFail($id);
    $peminjaman->update(['status' => 'ditolak']);

    return back()->with('danger', 'Peminjaman ruangan berhasil ditolak.');
}


// --- FASILITAS ACTIONS ---

public function setujuiFasilitas($id)
{
    $peminjaman = PeminjamanFasilitas::findOrFail($id);
    $peminjaman->update(['status' => 'disetujui']);

    return back()->with('success', 'Peminjaman fasilitas berhasil disetujui.');
}

public function tolakFasilitas($id)
{
    $peminjaman = PeminjamanFasilitas::findOrFail($id);
    $peminjaman->update(['status' => 'ditolak']);

    return back()->with('danger', 'Peminjaman fasilitas berhasil ditolak.');
}
}
