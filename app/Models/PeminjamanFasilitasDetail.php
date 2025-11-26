<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanFasilitasDetail extends Model
{
    protected $table = 'peminjaman_fasilitas_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'peminjaman_fasilitas_id',
        'nama_fasilitas',
        'jumlah'
    ];
}
