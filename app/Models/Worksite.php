<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Enums\WorksitePaymentStatusEnum;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Enums\WorksiteTypeEnum;
use App\Observers\WorksiteObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Worksite extends Model
{
    protected $fillable = [
        'name',
        'type',
        'location',
        'start_date',
        'end_date',
        'address',
        'number',
        'city',
        'zip_code',
        'province',
        'country',
        'notes',
        'customer_id',
        'daily_cost',
        'extra_time_cost',
        'daily_hours',
        'daily_allowance',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => WorksiteStatusEnum::class,
        'type' => WorksiteTypeEnum::class,
        'daily_cost' => MoneyCast::class,
        'extra_time_cost' => MoneyCast::class,
        'daily_allowance' => MoneyCast::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::observe(WorksiteObserver::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function work_days()
    {
        return $this->hasMany(WorkDay::class);
    }

    public function outgoings()
    {
        return $this->hasManyThrough(
            Outgoing::class,
            WorkDay::class,
            'worksite_id',
            'work_day_id',
            'id',
            'id'
        );
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public function not_payed()
    {
        return $this->hasMany(DocumentWorksite::class)->whereNotExists()->dd();
    }

    public function document_row()
    {
        return $this->morphOne(DocumentRow::class, 'productable');
    }

    public function getFullAddressAttribute()
    {
        if (empty($this->address) && empty($this->number) && empty($this->zip_code) && empty($this->city) && empty($this->province) && empty($this->country)) {
            return '';
        }

        return "{$this->address} {$this->number}, {$this->zip_code} {$this->city} ({$this->province}) {$this->country}";
    }

    public function getTotalRemunerationAttribute()
    {
        return $this->work_days->sum('total_remuneration');
    }

    public function getTotalExtraCostAttribute()
    {
        return $this->work_days->sum('total_extra_cost');
    }

    public function getRemainingAllowanceAttribute()
    {
        $remainingAllowance = $this->work_days->sum('remaining_allowance');

        return $remainingAllowance > 0
            ? $remainingAllowance
            : null;
    }

//    public function getTotalExtraCostAttribute()
//    {
//        return $this->work_days()->sum('extra_cost');
//    }
//
//    public function getTotalCostAttribute()
//    {
//        return $this->work_days()->sum('cost');
//    }
}
