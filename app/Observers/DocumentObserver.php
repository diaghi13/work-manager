<?php

namespace App\Observers;

use App\Models\Document;
use App\Services\DocumentService;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        $this->calculateTotals($document);
    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        $this->calculateTotals($document);
    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "restored" event.
     */
    public function restored(Document $document): void
    {
        //
    }

    /**
     * Handle the Document "force deleted" event.
     */
    public function forceDeleted(Document $document): void
    {
        //
    }

    protected function calculateTotals(Document $document)
    {
        $document->syncOriginal();

        $service = new DocumentService();
        $rows = $document->document_rows;
        $totals = $service->calculateTotals($rows->toArray());

        $document->net_price = $totals['net_price'];
        $document->vat_price = $totals['vat_price'];
        $document->gross_price = $totals['gross_price'];

        $document->save();
    }
}
