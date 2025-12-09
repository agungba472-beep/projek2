<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Ruangan; // Pastikan ini di-import
use Illuminate\Http\Request;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index()
    {
        // KOREKSI: Tambahkan eager loading relasi ruangan
        $aset = Aset::with('ruangan')->orderBy('aset_id', 'DESC')->get();
        return view('admin.aset.v_aset_index', compact('aset'));
    }

    public function create()
    {
        // Sudah benar: Ambil daftar ruangan yang sudah dibuat Admin
        $ruangan = Ruangan::all(); 
        
        return view('admin.aset.v_tambah_aset', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'nullable',
            'ruangan_id' => 'required|exists:ruangan,ruangan_id',
            'nilai' => 'nullable|numeric',
            'tahun_pengadaan' => 'nullable|numeric|digits:4|max:' . date('Y'),
            'kondisi' => 'required',
            'status' => 'required',
            'tanggal_peroleh' => 'nullable|date',
            'umur_maksimal' => 'nullable|numeric',
        ]);

        // Simpan data aset
        $aset = Aset::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'ruangan_id' => $request->ruangan_id,
            'nilai' => $request->nilai,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
            'tanggal_peroleh' => $request->tanggal_peroleh,
            'umur_maksimal' => $request->umur_maksimal,
            'tahun_pengadaan' => $request->tahun_pengadaan,
            'tanggal_input' => now(),
        ]);
        
        // Agar relasi ruangan tersedia saat generate QR setelah create()
        $aset->load('ruangan'); 

        // ==================================================
        //      GENERATE QR CODE DENGAN INFORMASI LENGKAP
        // ==================================================

        $dataQR = 
            "ID: " . $aset->aset_id . "\n" .
            "Nama: " . $aset->nama . "\n" .
            "Jenis: " . ($aset->jenis ?? '-') . "\n" .
            // Mengakses relasi ruangan yang sudah diload
            "Lokasi: " . ($aset->ruangan->nama_ruangan ?? '-') . "\n" .
            "Kondisi: " . $aset->kondisi . "\n" .
            "Status: " . $aset->status;

        $qr = QrCode::create($dataQR)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qr);

        $qrName = "qr_aset_" . $aset->aset_id . ".png";

        // Simpan QR ke storage/public/qrcode
        Storage::disk('public')->put("qrcode/" . $qrName, $result->getString());

        // Simpan nama file QR
        $aset->update([
            'qr_code' => $qrName
        ]);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan dan QR berisi data lengkap!');
    }

    public function edit($id)
    {
        // KOREKSI: Tambahkan pengambilan data ruangan
        $aset = Aset::findOrFail($id);
        $ruangan = Ruangan::all(); // <--- BARU
        
        return view('admin.aset.v_update_aset', compact('aset', 'ruangan')); // <--- BARU
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'ruangan_id' => 'required|exists:ruangan,ruangan_id', // Sudah benar
            'nilai' => 'nullable|numeric',
            'kondisi' => 'required',
            'status' => 'required',
            'tanggal_peroleh' => 'nullable|date',
            'tahun_pengadaan' => 'nullable|numeric|digits:4|max:' . date('Y'), // Sudah benar
            'umur_maksimal' => 'nullable|numeric',
        ]);

        Aset::where('aset_id', $id)->update($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Aset::where('aset_id', $id)->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus');
    }

    // ============================
    //  DOWNLOAD QR CODE
    // ============================
    public function downloadQR($id)
    {
        $aset = Aset::findOrFail($id);

        $file = storage_path("app/public/qrcode/" . $aset->qr_code);

        if (!file_exists($file)) {
            return back()->with('error', 'QR Code tidak ditemukan.');
        }

        return response()->download($file);
    }
}