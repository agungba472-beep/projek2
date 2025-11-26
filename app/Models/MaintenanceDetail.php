<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceDetail extends Model
{
    use HasFactory;

    protected $table = 'maintenance_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'maintenance_id',
        'deskripsi',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'tindakan',
        'foto',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }
    
}
