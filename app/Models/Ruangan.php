<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'ruangan_id';
    // Gunakan timestamps karena kita menambahkannya di skema SQL
    public $timestamps = true; 

    protected $fillable = [
        'nama_ruangan',
        'kepala_lab_id',
    ];

    /**
     * Relasi ke Dosen (Kepala Lab yang bertanggung jawab)
     */
    public function kepalaLab()
    {
        // Asumsi kita akan membuat Model Dosen.php
        return $this->belongsTo(Dosen::class, 'kepala_lab_id', 'dosen_id'); 
    }

    /**
     * Relasi ke Aset (aset apa saja yang ada di ruangan ini)
     */
    public function aset()
    {
        return $this->hasMany(Aset::class, 'ruangan_id', 'ruangan_id');
    }
}