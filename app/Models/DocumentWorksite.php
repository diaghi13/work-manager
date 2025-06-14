<?php

namespace App\Models;

use App\Models\Enums\WorksitePaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentWorksite extends Pivot
{
    protected $table = 'document_worksite';

    public $timestamps = true;

    protected $fillable = [
        'document_id',
        'worksite_id',
        'worksite_payment_status',
    ];

    protected $casts = [
        'worksite_payment_status' => WorksitePaymentStatusEnum::class,
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function worksite()
    {
        return $this->belongsTo(Worksite::class);
    }
}
