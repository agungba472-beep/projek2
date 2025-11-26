<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property int $user_id
 * @property string $username
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class User extends Authenticatable
{
    // kalau tabelmu bernama 'user'
    protected $table = 'user';

    // primary key di db: user_id
    protected $primaryKey = 'user_id';

    // jika primary key auto increment integer (default true)
    public $incrementing = true;
    protected $keyType = 'int';

    // jika tabel punya created_at, kalau tidak ada set false
    public $timestamps = false; // ubah ke false jika tidak punya created_at / updated_at
    protected $guarded = [];
    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
        'last_seen'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // jika password sudah bcrypt, oke. jika tidak, kamu bisa disable casting 'hashed' jika pakai Laravel lama
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'last_seen' => 'datetime',
        ];
    }
    public function maintenance()
{
    return $this->hasMany(Maintenance::class, 'teknisi_id', 'user_id');
}

}
