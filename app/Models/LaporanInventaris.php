<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanInventaris extends Model
{
    protected $table = 'laporan_inventaris';
    protected $primaryKey = 'laporan_id';
    public $timestamps = false;

    protected $fillable = [
        'periode',
        'tanggal_dibuat'
    ];

    public function detail()
    {
        return $this->hasMany(LaporanInventarisDetail::class, 'laporan_id', 'laporan_id');
    }
}
