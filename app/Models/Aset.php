<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';
    protected $primaryKey = 'aset_id';

    protected $fillable = [
        'nama',
        'jenis',
        'lokasi',
        'tanggal_peroleh',
        'umur_maksimal',
        'nilai',
        'kondisi',
        'status',
        'tanggal_input',
        'laporan_id'
    ];

    public $timestamps = false;

    public function laporan()
    {
        return $this->belongsTo(LaporanInventaris::class, 'laporan_id', 'laporan_id');
    }
}
