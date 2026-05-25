<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Link extends Model
{
    protected $table = 't_link';

    protected $primaryKey = 'id_link';

    public $timestamps = false;

    protected $fillable = [
        'id_link', 'id_kategori', 'nik', 'nama_web', 'url', 'deskripsi',
        'tag', 'role', 'status', 'hit_point',
        'status_checked_at', 'status_http_code',
        'status_response_time_ms', 'status_summary',
    ];

    protected $casts = [
        'status_checked_at' => 'datetime',
        'status_http_code' => 'integer',
        'status_response_time_ms' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function getNormalizedUrlAttribute(): string
    {
        $url = trim((string) $this->url);

        if ($url === '') {
            return '';
        }

        if (! preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://'.ltrim($url, '/');
        }

        return $url;
    }

    public function getResolvedStatusAttribute(): string
    {
        $status = Str::lower(trim((string) $this->status));

        return $status !== '' ? $status : 'tidak diketahui';
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 't_link_tag', 'id_link', 'id_tag');
    }
}
