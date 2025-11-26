<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        $user = User::orderBy('user_id', 'DESC')->get();
        return view('admin.manajemen.v_kelola_pengguna', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                Rule::unique((new User)->getTable(), 'username')
 // FIX: pakai tabel 'user' sesuai model
            ],
            'password' => 'required|min:5',
            'role' => 'required'
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->status = 'nonaktif';
        $user->save();

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required',
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data pengguna diperbarui');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Pengguna dihapus');
    }
}
