<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan Model User diimport

class Komplain extends Model
{
    protected $table = 'komplain';
    protected $primaryKey = 'komplain_id';
    protected $fillable = [
        'user_id',
        'kategori',
        'deskripsi',
        'status',
        'assigned_to',
        'level_admin',
        'level_teknisi',
        'catatan_teknisi',
        'sla_deadline',
    ];

    // Relasi ke User (Pelapor Komplain)
    public function user()
    {
        // Asumsi foreign key user_id merujuk ke id/user_id di tabel users
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Relasi ke Teknisi (User yang Ditugaskan)
    public function teknisi()
    {
        // Menggunakan foreign key assigned_to untuk merujuk ke User teknisi
        return $this->belongsTo(User::class, 'assigned_to'); 
    }
}