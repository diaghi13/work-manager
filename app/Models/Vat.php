<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
    protected $fillable = [
        'code',
        'description',
        'vat_nature',
        'value',
    ];
}
