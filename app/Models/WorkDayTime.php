<?php

namespace App\Models;

use App\Observers\WorkDayTimeObserver;
use Illuminate\Database\Eloquent\Model;

class WorkDayTime extends Model
{
    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'notes',
        'work_day_id'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::observe(WorkDayTimeObserver::class);
    }

    public function work_day()
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function getTotalHoursAttribute()
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime, true) / 60;
    }
}
