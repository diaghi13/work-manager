<?php

namespace App\Models;

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
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    public function work_day()
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function attachments()
    {
        return $this->morphMany(UploadedFile::class, 'entitable');
    }
}
