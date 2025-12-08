<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $table = 'peminjaman_ruangan';

    protected $primaryKey = 'peminjaman_ruangan_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nama_ruangan',
        'nama_peminjam',
        'mata_kuliah',
        'dosen_pengampu',
        'jam_pinjam',
        'jam_kembali',
        'status',
        'bukti_foto'
    ];
}
