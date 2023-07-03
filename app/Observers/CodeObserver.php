<?php

namespace App\Observers;

use App\Models\User;

class CodeObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Get the last inserted model
        $delegate = User::query()->latest()->first();

        // Generate the version number
        $versionNumber = 'D' . str_pad($delegate->id, 4, '0', STR_PAD_LEFT);

        // Update the code field
        $delegate->code = $versionNumber;
        $delegate->save();
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
