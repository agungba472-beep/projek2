<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function index()
{
    $userId = session('user_id');

    $user = DB::table('mahasiswa')
            ->where('user_id', $userId)
            ->first();

    return view('v_profile', compact('user'));
}


   public function update(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'nim' => 'required',
        'kelas' => 'required',
    ]);

    DB::table('mahasiswa')
        ->where('user_id', session('user_id'))
        ->update([
            'nama'  => $request->nama,
            'nim'   => $request->nim,
            'kelas' => $request->kelas,
        ]);

    return back()->with('success', 'Profil berhasil diperbarui!');
}

}
