<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Enums\DocumentStatusEnum;
use App\Models\Enums\DocumentTypeEnum;
use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'type',
        'ref_number',
        'document_date',
        'customer_id',
        'payment_method_id',
        'status',
        'notes',
        'net_price',
        'vat_price',
        'gross_price',
    ];

    protected $casts = [
        'type' => DocumentTypeEnum::class,
        'document_date' => 'date',
        'status' => DocumentStatusEnum::class,
        'net_price' => MoneyCast::class,
        'vat_price' => MoneyCast::class,
        'gross_price' => MoneyCast::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::observe(DocumentObserver::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function worksites()
    {
        return $this->belongsToMany(Worksite::class, 'document_worksite', 'document_id', 'worksite_id')
            ->withPivot('worksite_payment_status')
            ->withTimestamps();
    }

    public function documentWorksites()
    {
        return $this->hasMany(DocumentWorksite::class);
    }

//    public function available_worksites()
//    {
//        return Worksite::whereNotIn('id', DocumentWorksite::all()->pluck('worksite_id'));
//    }

    public function document_rows()
    {
        return $this->hasMany(DocumentRow::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

//    public function document_worksite(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Worksite::class);
//    }
}
