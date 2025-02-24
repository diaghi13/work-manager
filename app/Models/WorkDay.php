<?php

namespace App\Models;

use App\Models\Enums\OutgoingTypeEnum;
use App\Observers\WorkDayObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    protected $fillable = [
        'type',
        'date',
        'description',
        'start_date_time',
        'end_date_time',
        'extra_cost_description',
        'calculate_extra_time_cost',
        'worksite_id',
        'total_hours', // calculated
        'extra_time', // calculated
        'extra_time_cost', // calculated
        'daily_cost',
        'daily_allowance',
        'total_remuneration', // calculated
        'total_extra_cost', // calculated
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'calculate_extra_cost' => 'boolean',
    ];

    protected $with = [
        //'outgoings',
        //'worksite',
    ];

    protected $appends = [
//        'total_hours',
//        'extra_time',
//        'extra_time_cost',
//        'daily_cost',
//        'total_remuneration',
//        'total_extra_cost',
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(WorkDayObserver::class);
    }

    public function work_day_datetimes()
    {
        return $this->hasMany(WorkDayTime::class);
    }

    public function worksite()
    {
        return $this->belongsTo(Worksite::class);
    }

    public function active_worksites()
    {
        return $this->belongsTo(Worksite::class)
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today());
    }

    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,
            Worksite::class,
            'id',
            'id',
            'worksite_id',
            'customer_id'
        );
    }

    public function outgoings()
    {
        return $this->hasMany(Outgoing::class);
    }

    // da spostare nella classe WorkDayDateTime
//    public function getTotalHoursAttribute()
//    {
//        $totalDuration = (new Carbon($this->end_date_time))->diffInMinutes(new Carbon($this->start_date_time), true);
//
//        return $totalDuration / 60;
//    }

//    public function getExtraTimeAttribute()
//    {
//        $dailyHours = $this->worksite->daily_hours;
//
//        if ($this->total_hours > $dailyHours) {
//            return $this->total_hours - $dailyHours;
//        }
//
//        return 0;
//    }

//    public function getExtraTimeCostAttribute()
//    {
//        return $this->calculate_extra_cost ? $this->extra_time * $this->worksite->extra_time_cost : 0;
//    }

//    public function getDailyCostAttribute()
//    {
//        return (float)$this->worksite->daily_cost;
//    }

//    public function getTotalRemunerationAttribute()
//    {
//        return $this->daily_cost + $this->extra_time_cost;
//    }

    public function getTravelCostAttribute()
    {
        return $this->outgoings->filter(function ($outgoing) {
            return $outgoing->type === OutgoingTypeEnum::TRAVEL || $outgoing->type === OutgoingTypeEnum::FUEL;
        })->sum('amount');
    }

    public function getMealCostAttribute()
    {
        return $this->outgoings->where('type', OutgoingTypeEnum::MEAL)->sum('amount');
    }

    public function getExtraCostAttribute()
    {
        $result = $this->outgoings->filter(function ($outgoing) {
            return $outgoing->type === OutgoingTypeEnum::EXTRA || $outgoing->type === OutgoingTypeEnum::OTHER;
        })->sum('amount');

        return $result;
    }

    public function getTotalExtraCostAttribute()
    {
        if ($this->outgoings->count() > 0) {
            return $this->outgoings->sum('amount');
        }

        return 0;
    }

    public function getTotalCostAttribute()
    {
        return $this->total_remuneration + $this->total_extra_cost;
    }
}
