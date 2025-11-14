<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // tabel yang digunakan
    protected $table = 'user';

    // primary key
    protected $primaryKey = 'user_id';

    // field yang dapat diisi
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    // karena tabel kamu tidak punya created_at dan updated_at
    public $timestamps = false;

    // tidak ada hidden remember_token karena tabel kamu tidak pakai
    protected $hidden = [
        'password',
    ];
}
