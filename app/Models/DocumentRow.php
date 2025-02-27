<?php

namespace App\Models;

use App\Observers\DocumentRowObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class DocumentRow extends Model
{
    protected $fillable = [
        'order',
        'document_id',
        'productable_id',
        'productable_type',
        'description',
        'measure_unit_id',
        'quantity',
        'price',
        'vat_id',
        'vat',
        'total',
        'notes',
    ];

    protected function quantity(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

//    protected function vat(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => $value / 100,
//            set: fn ($value) => $value * 100
//        );
//    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    protected static function boot()
    {
        parent::boot();

        //static::observe(DocumentRowObserver::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function measure_unit()
    {
        return $this->belongsTo(MeasureUnit::class);
    }

    public function vat()
    {
        return $this->belongsTo(Vat::class);
    }

    public function productable()
    {
        return $this->morphTo();
    }
}
