<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 't_kategori';

    protected $primaryKey = 'id_kategori';

    public $timestamps = false;

    protected $fillable = ['id_kategori', 'nama_kategori', 'nik', 'icon'];

    public function links()
    {
        return $this->hasMany(Link::class, 'id_kategori', 'id_kategori');
    }
}
