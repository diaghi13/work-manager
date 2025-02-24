<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedFile extends Model
{
    protected $fillable = [
        'filename',
        'title',
        'slug',
        'mime_type',
        'path',
        'size',
        'disk',
    ];

    public function entitable()
    {
        return $this->morphTo();
    }
}
