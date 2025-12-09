<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'dosen_id';
    public $timestamps = false; // Berdasarkan skema SQL, tidak ada created_at/updated_at di tabel `dosen`

    protected $fillable = [
        'user_id',
        'nama',
        'nidn',
        'prodi',
    ];

    /**
     * Relasi ke User (akun login)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Ruangan (ruangan yang diampu sebagai Kepala Lab)
     * (Revisi 8)
     */
    public function ruanganDiampu()
    {
        return $this->hasMany(Ruangan::class, 'kepala_lab_id', 'dosen_id');
    }
}