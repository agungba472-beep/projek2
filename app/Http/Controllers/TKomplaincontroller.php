<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TKomplainController extends Controller
{
    public function index()
    {
        $teknisi_id = Auth::id();

        $data = Komplain::where('assigned_to', $teknisi_id)
            ->orderBy('komplain_id', 'DESC')
            ->get();

        return view('teknisi.komplain.index', compact('data'));
    }

    public function show($id)
    {
        $k = Komplain::with('user')->findOrFail($id);

        // pastikan tidak bisa lihat komplain orang lain
        if ($k->assigned_to != Auth::id()) {
            abort(403, 'Tidak boleh akses komplain orang lain');
        }

        return view('teknisi.komplain.show', compact('k'));
    }

    public function update(Request $req, $id)
    {
        $k = Komplain::findOrFail($id);

        if ($k->assigned_to != Auth::id()) {
            abort(403);
        }

        // update level versi teknisi
        if ($req->level_teknisi) {
            $k->level_teknisi = $req->level_teknisi;
        }

        // update catatan
        if ($req->catatan_teknisi) {
            $k->catatan_teknisi = $req->catatan_teknisi;
        }

        // upload bukti foto
        if ($req->hasFile('bukti_foto')) {
            $file = $req->file('bukti_foto');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move('bukti', $name);
            $k->bukti_foto = $name;
        }

        // update status
        $k->status = $req->status;

        // // kalau selesai → hitung SLA selesai
        // if ($req->status == 'selesai') {
        //     $k->completed_at = now();
        // }

        $k->save();

        return redirect()->back()->with('success', 'Komplain berhasil diperbarui.');
    }
}
