<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    protected $table = 'komplain';
    protected $primaryKey = 'komplain_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'peminjaman_id',
        'kategori',
        'deskripsi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
