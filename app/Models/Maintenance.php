<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    public $timestamps = false;

    protected $table = 'maintenance';
    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
        'aset_id',
        'teknisi_id',
        'jenis',
        'tanggal_dijadwalkan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    /**
     * Relasi ke tabel aset
     * aset_id → aset.aset_id
     */
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id', 'aset_id');
    }

    /**
     * Relasi ke tabel users untuk teknisi
     * teknisi_id → users.id
     */
    // app/Models/Maintenance.php
public function teknisi()
{
    return $this->belongsTo(User::class, 'teknisi_id', 'user_id'); 
}

    /**
     * Relasi ke tabel detail maintenance
     */
    public function details()
    {
        return $this->hasMany(MaintenanceDetail::class, 'maintenance_id', 'maintenance_id');
    }
}
