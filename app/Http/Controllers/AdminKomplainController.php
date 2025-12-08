<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use App\Models\User;
use Illuminate\Http\Request;

class AdminKomplainController extends Controller
{
    public function index()
    {
        $data = Komplain::with('user')->orderBy('komplain_id', 'DESC')->get();
        return view('admin.komplain.index', compact('data'));
    }

    public function show($id)
    {
        $komplain = Komplain::with('user', 'teknisi')->findOrFail($id);

        // List teknisi (role = teknisi)
        $teknisi = User::where('role', 'teknisi')->get();

        return view('admin.komplain.show', compact('komplain', 'teknisi'));
    }

    /** ======================================================
     *   FUNCTION PROSES (ADMIN MULAI MENANGANI KOMPLAIN)
     * ======================================================*/
    public function proses($id)
    {
        $k = Komplain::findOrFail($id);

        // Hanya ubah status ke "diproses"
        if ($k->status == 'menunggu') {
            $k->status = 'diproses';
            $k->save();
        }

        return redirect()->back()->with('success', 'Komplain mulai diproses!');
    }

    /** ======================================================
     * UPDATE ADMIN (ASSIGN TEKNISI + LEVEL KERUSAKAN)
     * ======================================================*/
    public function update(Request $req, $id)
    {
        $k = Komplain::findOrFail($id);

        $k->assigned_to = $req->assigned_to;
        $k->level_admin = $req->level_admin;

        // Jika admin assign teknisi → status otomatis jadi diproses
        if ($req->assigned_to && $k->status == 'menunggu') {
            $k->status = 'diproses';
        }

        // Set SLA berdasarkan level kerusakan
        if ($req->level_admin == 'minor') {
            $k->sla_deadline = now()->addHours(1.5);
        } elseif ($req->level_admin == 'major') {
            $k->sla_deadline = now()->addDay();
        }

        $k->save();

        return redirect()->route('admin.komplain.index')->with('success', 'Komplain berhasil diperbarui!');
    }
    public function riwayat()
    {
        // Menggunakan relasi 'user' (pelapor) dan 'teknisi' (yang ditugaskan)
        $data = Komplain::with('user', 'teknisi') 
            ->orderBy('komplain_id', 'DESC')
            ->get();

        return view('admin.komplain.riwayat', compact('data'));
    }

    public function destroy($id)
    {
        $k = Komplain::findOrFail($id);
        $k->delete();

        return redirect()->back()->with('success', 'Komplain berhasil dihapus.');
    }
}
