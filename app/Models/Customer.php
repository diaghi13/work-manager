<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'vat_code',
        'vat_id',
        'street',
        'number',
        'zip_code',
        'city',
        'state',
        'country',
        'sdi_code',
        'daily_cost',
        'extra_time_cost',
        'daily_hours',
    ];

    public function worksites()
    {
        return $this->hasMany(Worksite::class);
    }
}
