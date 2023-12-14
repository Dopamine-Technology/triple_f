<?php

namespace App\Observers;

use App\Models\Club;
use App\Models\User;
use Filament\Notifications\Notification;

class ClubObserver
{
    /**
     * Handle the Club "created" event.
     */
    public function created(Club $club): void
    {
        $admins = User::query()->where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Notification::make()
                ->title('New Club Account Created and need  admin action to approve it  : ' . $club->name)
                ->sendToDatabase($admin);
        }
    }

    /**
     * Handle the Club "updated" event.
     */
    public function updated(Club $club): void
    {
        //
    }

    /**
     * Handle the Club "deleted" event.
     */
    public function deleted(Club $club): void
    {
        //
    }

    /**
     * Handle the Club "restored" event.
     */
    public function restored(Club $club): void
    {
        //
    }

    /**
     * Handle the Club "force deleted" event.
     */
    public function forceDeleted(Club $club): void
    {
        //
    }
}
