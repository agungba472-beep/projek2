<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $table = 'peminjaman_ruangan';
    protected $primaryKey = 'peminjaman_ruangan_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama_peminjam',
        'nama_ruangan',
        'mata_kuliah',
        'dosen_pengampu',
        'jam_pinjam',
        'jam_kembali',
        'status'
    ];
}
