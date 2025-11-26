<?php

namespace App\Http\Controllers;

use App\Models\Komplain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
   public function index()
{
    $role = Auth::user()->role;

    $data = Komplain::orderBy('komplain_id', 'DESC')->get();

    if ($role === 'Mahasiswa') {
        return view('mahasiswa.peminjaman.v_komplain', compact('data'));
    }

    if ($role === 'Dosen') {
        return view('dosen.peminjaman.v_komplain', compact('data'));
    }

    abort(403, 'Anda tidak memiliki akses.');
}


    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        Komplain::create([
            'user_id' => Auth::id(),
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'status' => 'menunggu'
        ]);

        return back()->with('success', 'Komplain berhasil dikirim!');
    }
}
