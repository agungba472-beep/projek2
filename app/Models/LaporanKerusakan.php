<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'aset_id', 'deskripsi', 'status','tahun_kerusakan', 'tanggal_lapor'
    ];

    public $timestamps = false;

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id', 'aset_id');
    }
}
