<?php

namespace App\Observers;

use App\Jobs\ProcessTenantDatabaseCreationJob;
use App\Jobs\ProcessTenantMigrationJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->syncOriginal();

        $databaseName = 'wm_tenant_' . Str::random(8) . '_' . $user->id;

        if ($user->database) {
            return;
        }

        $user->update(['database' => $databaseName]);

        Bus::chain([
            new ProcessTenantDatabaseCreationJob($databaseName),
            new ProcessTenantMigrationJob($databaseName),
        ])->dispatch();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
