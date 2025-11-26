<?php
namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\MaintenanceDetail;
use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MaintenanceController extends Controller
{
    public function index()
    {
        $data = Maintenance::with(['aset','teknisi'])
            ->orderBy('maintenance_id','DESC')
            ->get();

        $aset = Aset::all();

        return view('teknisi.maintenance.v_index', compact('data','aset'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id' => 'nullable',
            'jenis' => 'nullable',
            'tanggal_dijadwalkan' => 'nullable|date',
        ]);

        Maintenance::create([
            'aset_id' => $request->aset_id,
            'teknisi_id' => $request->teknisi_id,
            'jenis' => $request->jenis,
            'tanggal_dijadwalkan' => $request->tanggal_dijadwalkan,
            'status' => 'Terjadwal',
            'catatan' => $request->catatan,
        ]);

        return back()->with('success','Maintenance berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aset_id' => 'nullable',
            'jenis' => 'nullable',
            'tanggal_dijadwalkan' => 'nullable|date',
            'status' => 'nullable',
        ]);

        $m = Maintenance::findOrFail($id);
        $m->update($request->all());

        return back()->with('success','Data maintenance berhasil diperbarui');
    }

  public function delete($id)
{
    // Hapus baris dd($id); atau dd(ID yang Anda masukkan);

    $m = Maintenance::findOrFail($id);
    $m->delete();

    return back()->with('success', 'Maintenance berhasil dihapus');
}

    
    public function show($id)
    {
        $maintenance = Maintenance::with(['aset','teknisi','details'])->findOrFail($id);
        return view('teknisi.maintenance.v_detail', compact('maintenance'));
    }
   public function listByAset($id)
{
    $aset = Aset::findOrFail($id);

    $maintenance = Maintenance::where('aset_id', $id)
        ->orderBy('tanggal_dijadwalkan', 'DESC')
        ->get();

    // Ambil semua teknisi dari users table
    $teknisi = User::where('role', 'Teknisi')->get();

    return view('admin.aset.list_by_aset', compact('aset', 'maintenance', 'teknisi'));
}



    public function storeDetail(Request $request)
    {
        $request->validate([
            'maintenance_id' => 'nullable',
            'deskripsi' => 'nullable',
            'tindakan' => 'nullable',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('maintenance','public');
        }

        MaintenanceDetail::create([
            'maintenance_id' => $request->maintenance_id,
            'deskripsi' => $request->deskripsi,
            'kondisi_sebelum' => $request->kondisi_sebelum,
            'kondisi_sesudah' => $request->kondisi_sesudah,
            'tindakan' => $request->tindakan,
            'foto' => $foto,
        ]);
        
        
        return back()->with('success','Detail maintenance ditambahkan');
    }
    
}
