<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 't_tag';
    protected $primaryKey = 'id_tag';
    public $timestamps = false;
    protected $fillable = ['nama_tag'];

    public function links()
    {
        return $this->belongsToMany(Link::class, 't_link_tag', 'id_tag', 'id_link');
    }
}
