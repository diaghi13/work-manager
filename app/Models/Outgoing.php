<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Enums\OutgoingTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Outgoing extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
        'work_day_id',
        'attachments',
        'original_filenames',
    ];

    protected $casts = [
        'type' => OutgoingTypeEnum::class,
        'attachments' => 'array',
        'original_filenames' => 'array',
        'amount' => MoneyCast::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::observe(\App\Observers\OutgoingObserver::class);
    }

    public function work_day()
    {
        return $this->belongsTo(WorkDay::class);
    }

//    public function attachments()
//    {
//        return $this->morphMany(UploadedFile::class, 'entitable');
//    }
}
