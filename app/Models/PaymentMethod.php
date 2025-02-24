<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'payment_method',
        'number_of_installments',
        'end_of_month',
    ];

    protected $casts = [
        'end_of_month' => 'boolean',
    ];

    protected $with = ['paymentMethodInstallments'];

    public function paymentMethodInstallments()
    {
        return $this->hasMany(PaymentMethodInstallment::class);
    }
}
