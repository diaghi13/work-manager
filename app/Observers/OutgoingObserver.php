<?php

namespace App\Observers;

use App\Models\Outgoing;
use Illuminate\Support\Facades\Storage;

class OutgoingObserver
{
    /**
     * Handle the Outgoing "created" event.
     */
    public function created(Outgoing $outgoing): void
    {
        //
    }

    /**
     * Handle the Outgoing "updating" event.
     */
    public function updating(Outgoing $outgoing): void
    {

    }

    /**
     * Handle the Outgoing "updated" event.
     */
    public function updated(Outgoing $outgoing): void
    {
        $originalsAttachments = collect($outgoing->getOriginal()['attachments']);
        $newAttachments = collect($outgoing->attachments);

        $diff = $originalsAttachments->diff($newAttachments);

        foreach ($diff as $attachment) {
            // delete attachment
            Storage::disk('local')->delete($attachment);
        }
    }

    /**
     * Handle the Outgoing "deleted" event.
     */
    public function deleted(Outgoing $outgoing): void
    {
        $attachments = collect($outgoing->attachments);

        foreach ($attachments as $attachment) {
            // delete attachment
            Storage::disk('local')->delete($attachment);
        }
    }

    /**
     * Handle the Outgoing "restored" event.
     */
    public function restored(Outgoing $outgoing): void
    {
        //
    }

    /**
     * Handle the Outgoing "force deleted" event.
     */
    public function forceDeleted(Outgoing $outgoing): void
    {
        //
    }
}
