<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitas extends Model
{
    protected $table = 'peminjaman_fasilitas';
    protected $primaryKey = 'peminjaman_fasilitas_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama_peminjam',
        'jam_pinjam',
        'jam_kembali',
        'status'
    ];

    public function detail()
    {
        return $this->hasMany(PeminjamanFasilitasDetail::class, 'peminjaman_fasilitas_id');
    }
}
