<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 't_admin';
    protected $primaryKey = 'nik_admin';
    public $timestamps = false;

    protected $fillable = [
        'nik_admin',
        'nama',
        'password',
    ];

    protected $hidden = ['password'];

    /**
     * Password di t_admin disimpan plaintext.
     * Override getAuthPassword agar framework
     * bisa tetap melakukan pengecekan via Auth::attempt.
     *
     * Kita TIDAK hash password di sini supaya cocok dgn data lama.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
