<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodInstallment extends Model
{
    protected $fillable = [
        'days',
        'payment_method_id',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
