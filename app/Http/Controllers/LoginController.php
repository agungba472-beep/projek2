<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('v_login');
    }

   public function proses(Request $request)
{
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        // 🔥 Update status online + last_seen
        auth()->user()->update([
            'status'     => 'aktif',
            'last_seen'  => now(),
        ]);

        $role = auth()->user()->role;

        return match ($role) {
            'Admin'     => redirect('/admin/dashboard'),
            'Mahasiswa' => redirect('/mahasiswa/dashboard'),
            'Dosen'     => redirect('/dosen/dashboard'),
            'Teknisi'   => redirect('/teknisi/dashboard'),
            default     => redirect('/')
        };
    }

    return back()->with('error', 'Username atau Password salah.');
}

public function logout(Request $request)
{
    // 🔥 Update offline
    auth()->user()->update([
        'status'     => 'nonaktif',
        'last_seen'  => now(),
    ]);

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}
}