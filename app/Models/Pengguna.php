<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 't_pengguna';

    protected $primaryKey = 'nik';

    public $timestamps = false;

    protected $fillable = [
        'nik',
        'username',
        'nama_user',
        'password',
        'email',
        'jabatan',
        'foto',
    ];

    protected $hidden = ['password'];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
