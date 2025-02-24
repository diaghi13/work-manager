<?php

namespace App\Observers;

use App\Models\DocumentRow;
use App\Models\Worksite;

class DocumentRowObserver
{
    /**
     * Handle the DocumentRow "created" event.
     */
    public function created(DocumentRow $documentRow): void
    {
        $this->ensureWorksiteIsPayed($documentRow);
    }

    /**
     * Handle the DocumentRow "updated" event.
     */
    public function updated(DocumentRow $documentRow): void
    {
        $this->ensureWorksiteIsPayed($documentRow);
    }

    /**
     * Handle the DocumentRow "deleted" event.
     */
    public function deleted(DocumentRow $documentRow): void
    {
        //
    }

    /**
     * Handle the DocumentRow "restored" event.
     */
    public function restored(DocumentRow $documentRow): void
    {
        //
    }

    /**
     * Handle the DocumentRow "force deleted" event.
     */
    public function forceDeleted(DocumentRow $documentRow): void
    {
        //
    }

    /**
     * @param DocumentRow $documentRow
     * @return void
     */
    protected function ensureWorksiteIsPayed(DocumentRow $documentRow): void
    {
        if ($documentRow->productable_type === Worksite::class) {
            $all = DocumentRow::where('productable_id', $documentRow->productable_id)
                ->where('productable_type', Worksite::class)
                ->get();

            $total = $all->sum('price');

            $worksite = Worksite::find($documentRow->productable_id);

            if ($worksite->total_remuneration === $total) {
                $worksite->is_payed = true;
                $worksite->save();
            } else {
                $worksite->is_payed = false;
                $worksite->save();
            }
        }
    }
}
