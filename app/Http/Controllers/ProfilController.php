<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = null;

        switch ($user->role) {
            case 'Mahasiswa':
                $profile = DB::table('mahasiswa')->where('user_id', $user->user_id)->first();
                break;
            case 'Dosen':
                $profile = DB::table('dosen')->where('user_id', $user->user_id)->first();
                break;
            case 'Teknisi':
                $profile = DB::table('teknisi')->where('user_id', $user->user_id)->first();
                break;
            case 'Admin':
                $profile = DB::table('admin')->where('user_id', $user->user_id)->first();
                break;
        }

        return view('v_profile', compact('user', 'profile'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $table = strtolower($user->role);

        // Cek apakah data profil sudah ada
        $exists = DB::table($table)->where('user_id', $user->user_id)->first();

        $data = ['nama' => $request->nama];

        if ($user->role === 'Mahasiswa') {
            $data['nim'] = $request->nim;
            $data['kelas'] = $request->kelas;
        }

        if ($user->role === 'Dosen') {
            $data['nidn'] = $request->nidn;
            $data['prodi'] = $request->prodi;
        }

        if ($user->role === 'Teknisi') {
            $data['bidang'] = $request->bidang;
        }

        if ($user->role === 'Admin') {
            $data['jabatan'] = $request->jabatan;
        }


        if (!$exists) {
            $data['user_id'] = $user->user_id;
            DB::table($table)->insert($data);
        } else {
            DB::table($table)->where('user_id', $user->user_id)->update($data);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
