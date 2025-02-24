<?php

namespace App\Models;

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

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function worksite()
    {
        return $this->belongsTo(Worksite::class);
    }
}
