<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'expiration_date',
        'payment_date',
        'amount',
        'payment_method_id',
        'document_id',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'payment_date' => 'date',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
