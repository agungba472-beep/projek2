<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
   public function index()
{
    $aset = Aset::orderBy('aset_id', 'DESC')->get();
    return view('admin.aset.v_aset_index', compact('aset'));
}

    public function create()
    {
        return view('admin.aset.v_tambah_aset');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'nullable',
            'lokasi' => 'nullable',
            'nilai' => 'nullable|numeric',
            'kondisi' => 'required',
            'status' => 'required'
        ]);

        Aset::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'lokasi' => $request->lokasi,
            'nilai' => $request->nilai,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
            'tanggal_peroleh' => $request->tanggal_peroleh,
            'umur_maksimal' => $request->umur_maksimal,
            'tanggal_input' => now(),
            'laporan_id' => null
        ]);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan!');
    }

    public function edit($id)
{
    $aset = Aset::findOrFail($id);
    return view('admin.aset.v_update_aset', compact('aset'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'jenis' => 'nullable|string|max:50',
        'lokasi' => 'nullable|string|max:100',
        'nilai' => 'nullable|numeric',
        'kondisi' => 'required',
        'status' => 'required',
        'tanggal_peroleh' => 'nullable|date',
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

}