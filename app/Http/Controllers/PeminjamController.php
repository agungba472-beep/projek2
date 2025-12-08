<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanFasilitas;
use App\Models\PeminjamanFasilitasDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Collection; 
use Illuminate\Support\Facades\DB; 

class PeminjamController extends Controller
{
    /* =======================================================
     * 1. PEMINJAMAN RUANGAN (MAHASISWA & DOSEN)
     * ======================================================= */
    // ... (Fungsi ruanganIndex, ruanganStore, hapusRuangan, kembalikanRuangan tetap sama) ...

    public function ruanganIndex()
    {
        $role = Auth::user()->role;
        // Hanya tampilkan peminjaman user yang sedang login
        $data = PeminjamanRuangan::where('user_id', Auth::id()) 
                ->orderBy('peminjaman_ruangan_id', 'DESC')->get();

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
            'nama_ruangan'      => 'required',
            'nama_peminjam'     => 'required',
            'mata_kuliah'       => 'required',
            'dosen_pengampu'    => 'required',
            'jam_pinjam'        => 'required|date',
        ]);
        
        // Cek Konflik Sederhana: apakah ruangan sudah 'Dipinjam' pada jam tersebut?
        $konflik = PeminjamanRuangan::where('nama_ruangan', $request->nama_ruangan)
            ->where('status', 'Dipinjam') 
            ->where(function($query) use ($request) {
                $query->where('jam_pinjam', '<=', $request->jam_pinjam)
                      ->whereNull('jam_kembali'); 
            })
            ->count();
            
        if ($konflik > 0) {
             return redirect()->back()->with('error', 'Ruangan sudah dipinjam pada jam tersebut. Peminjaman gagal.');
        }

        PeminjamanRuangan::create([
            'user_id'           => Auth::id(),
            'nama_ruangan'      => $request->nama_ruangan,
            'nama_peminjam'     => $request->nama_peminjam,
            'mata_kuliah'       => $request->mata_kuliah,
            'dosen_pengampu'    => $request->dosen_pengampu,
            'jam_pinjam'        => $request->jam_pinjam,
            'keterangan'        => $request->keterangan ?? null,
            'status'            => 'Dipinjam'
        ]);

        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil dicatat. Status: Dipinjam.');
    }

    public function hapusRuangan(Request $request)
    {
        $id = $request->id; 
        
        // Batalkan hanya jika milik user & status masih 'Dipinjam'
        $hapus = PeminjamanRuangan::where('peminjaman_ruangan_id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'Dipinjam') 
            ->delete();

        if ($hapus) {
            return back()->with('success', 'Peminjaman ruangan berhasil dibatalkan.');
        }

        return back()->with('error', 'Peminjaman tidak dapat dibatalkan. Pastikan status masih Dipinjam.');
    }

    public function kembalikanRuangan(Request $request, $id)
    {
        $request->validate([
            'bukti_foto'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = PeminjamanRuangan::where('peminjaman_ruangan_id', $id)
            ->where('status', 'Dipinjam')
            ->first();

        if (!$data) {
             return back()->with('error', 'Data peminjaman tidak ditemukan atau sudah dikembalikan.');
        }
        
        // Proses Upload Foto
        $fotoName = time() . '_' . $request->file('bukti_foto')->getClientOriginalName();
        $request->file('bukti_foto')->move(public_path('bukti_ruangan'), $fotoName); 

        $data->update([
            'jam_kembali'   => Carbon::now()->toDateTimeString(),
            'bukti_foto'    => $fotoName,
            'status'        => 'Dikembalikan'
        ]);

        return back()->with('success', 'Ruangan berhasil dikembalikan.');
    }
    
    /* =======================================================
     * 2. LOG PEMINJAMAN RUANGAN (ADMIN) & LOG FASILITAS MASTER VIEW
     * ======================================================= */
        
    public function ruanganLogIndex()
    {
        // 1. Mengambil semua riwayat peminjaman ruangan (untuk Log Ruangan, di blade menggunakan $data)
        $data = PeminjamanRuangan::orderBy('peminjaman_ruangan_id', 'DESC')->get();

        // 2. Mengambil semua riwayat peminjaman fasilitas (untuk Log Fasilitas, di blade menggunakan $fasilitas_log)
        $fasilitas_log = PeminjamanFasilitas::with('detail')
                                    ->orderBy('peminjaman_fasilitas_id', 'DESC')
                                    ->get();
                                    
        // 3. Mengambil pengajuan fasilitas yang statusnya 'menunggu' (untuk Pengajuan, di blade menggunakan $fasilitas_pengajuan)
        $fasilitas_pengajuan = PeminjamanFasilitas::with('detail')
                                    ->where('status', 'menunggu')
                                    ->orderBy('jam_pinjam', 'ASC')
                                    ->get();

        // Agar tidak ada error static analyzer PHP
        $ruangan_pengajuan = PeminjamanRuangan::hydrate([]); 

        return view('admin.fasilitas.v_pengajuan', compact('data', 'ruangan_pengajuan', 'fasilitas_pengajuan', 'fasilitas_log'));
    }
    
    public function hapusAdminRuangan($id)
    {
        $peminjaman = PeminjamanRuangan::find($id);

        if (!$peminjaman) {
            return back()->with('error', 'Log peminjaman tidak ditemukan.');
        }
        
        // Hapus file foto jika ada
        if ($peminjaman->bukti_foto) {
            $fotoPath = public_path('bukti_ruangan/' . $peminjaman->bukti_foto);
            if (File::exists($fotoPath)) {
                File::delete($fotoPath);
            }
        }

        $peminjaman->delete();
        
        return back()->with('success', 'Log peminjaman ruangan berhasil dihapus permanen.');
    }


    /* =======================================================
     * 3. PEMINJAMAN FASILITAS (USER) - DIPERBAIKI
     * ======================================================= */

    public function fasilitasIndex()
    {
        $role = Auth::user()->role;
        // Hanya tampilkan peminjaman user yang sedang login
        $data = PeminjamanFasilitas::with('detail')
                ->where('user_id', Auth::id())
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
            'nama_peminjam'     => 'required',
            'jam_pinjam'        => 'required|date',
            'jam_kembali_target'=> 'required|date|after:jam_pinjam', 
            'fasilitas'         => 'required|array',
            'jumlah'            => 'required|array'
        ]);

        $fasilitas = PeminjamanFasilitas::create([
            'user_id'       => Auth::id(),
            'nama_peminjam' => $request->nama_peminjam,
            'jam_pinjam'    => $request->jam_pinjam,
            'jam_kembali'   => $request->jam_kembali_target, 
            // PERUBAHAN: Status langsung diubah menjadi 'Dipinjam'
            'status'        => 'Dipinjam' 
        ]);

        foreach ($request->fasilitas as $index => $namaFasilitas) {
            $jumlah = $request->jumlah[$index] ?? 1;
            if ($jumlah > 0 && $namaFasilitas) {
                PeminjamanFasilitasDetail::create([
                    'peminjaman_fasilitas_id' => $fasilitas->peminjaman_fasilitas_id,
                    'nama_fasilitas'          => $namaFasilitas,
                    'jumlah'                  => $jumlah
                ]);
            }
        }

        return redirect()->back()->with('success', 'Peminjaman fasilitas berhasil dicatat. Status: Dipinjam.');
    }
    
    public function kembalikanFasilitas(Request $request, $id)
    {
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = PeminjamanFasilitas::where('peminjaman_fasilitas_id', $id)
            // PERUBAHAN: Status yang dicari adalah 'Dipinjam'
            ->where('status', 'Dipinjam') 
            ->first();

        if (!$data) {
            // Mengubah pesan error agar sesuai dengan alur baru
            return back()->with('error', 'Peminjaman fasilitas tidak ditemukan atau sudah dikembalikan.');
        }
        
        // Proses Upload Foto
        $fotoName = time() . '_' . $request->file('bukti_foto')->getClientOriginalName();
        $request->file('bukti_foto')->move(public_path('bukti_fasilitas'), $fotoName); 

        // Update status dan jam kembali aktual
        $data->update([
            'jam_kembali'   => Carbon::now()->toDateTimeString(), 
            'bukti_foto'    => $fotoName,
            'status'        => 'Dikembalikan'
        ]);

        return back()->with('success', 'Fasilitas berhasil dikembalikan dan dicatat.');
    }
    
    public function batalkanFasilitas($id)
    {
        $peminjaman = PeminjamanFasilitas::find($id);

        if (!$peminjaman) {
            return back()->with('error', 'Data peminjaman fasilitas tidak ditemukan.');
        }
        
        $currentStatus = strtolower($peminjaman->status);
        
        // PERUBAHAN: Hanya bisa dibatalkan jika statusnya 'Dipinjam'
        if ($currentStatus != 'dipinjam') {
             return back()->with('error', 'Peminjaman fasilitas sudah selesai atau tidak berstatus Dipinjam.');
        }

        try {
            DB::beginTransaction();
            // Hapus detail terkait
            PeminjamanFasilitasDetail::where('peminjaman_fasilitas_id', $id)->delete();
            
            // Hapus peminjaman utama
            $peminjaman->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Peminjaman fasilitas berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan peminjaman fasilitas. Silakan coba lagi.');
        }
    }


    /* =======================================================
     * 4. FUNGSI PENGAJUAN (ADMIN/DOSEN) - TIDAK ADA PERUBAHAN DI SINI
     * ======================================================= */
    
    // indexPengajuan kini hanya alias ke ruanganLogIndex() (Master Log Admin)
    public function indexPengajuan()
    {
        return $this->ruanganLogIndex();
    }

    public function setujuiFasilitas($id)
    {
        // PERHATIAN: Fungsi ini sekarang seharusnya TIDAK PERNAH dipanggil 
        // jika alur fasilitas sudah INSTAN (Dipinjam).
        $peminjaman = PeminjamanFasilitas::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);

        return back()->with('success', 'Peminjaman fasilitas berhasil disetujui.');
    }

    public function tolakFasilitas($id)
    {
        // PERHATIAN: Fungsi ini sekarang seharusnya TIDAK PERNAH dipanggil
        // jika alur fasilitas sudah INSTAN (Dipinjam).
        $peminjaman = PeminjamanFasilitas::findOrFail($id);
        $peminjaman->update(['status' => 'ditolak']);

        return back()->with('danger', 'Peminjaman fasilitas berhasil ditolak.');
    }
    
    public function hapusAdminFasilitas($id)
    {
        $peminjaman = PeminjamanFasilitas::find($id);

        if (!$peminjaman) {
            return back()->with('error', 'Log peminjaman fasilitas tidak ditemukan.');
        }
        
        try {
            DB::beginTransaction();
            // 1. Hapus file foto jika ada
            if ($peminjaman->bukti_foto) {
                $fotoPath = public_path('bukti_fasilitas/' . $peminjaman->bukti_foto);
                if (File::exists($fotoPath)) {
                    File::delete($fotoPath);
                }
            }
            
            // 2. Hapus detail terkait
            PeminjamanFasilitasDetail::where('peminjaman_fasilitas_id', $id)->delete();
            
            // 3. Hapus peminjaman utama
            $peminjaman->delete();
            
            DB::commit();
            return back()->with('success', 'Log peminjaman fasilitas berhasil dihapus permanen.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus log peminjaman fasilitas. Silakan coba lagi.');
        }
    }
}