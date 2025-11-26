<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanInventarisDetail extends Model
{
    protected $table = 'laporan_inventaris_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'laporan_id',
        'aset_id',
        'kondisi',
        'catatan',
        'tanggal_pemutihan'
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id', 'aset_id');
    }

    public function laporan()
    {
        return $this->belongsTo(LaporanInventaris::class, 'laporan_id', 'laporan_id');
    }
}
