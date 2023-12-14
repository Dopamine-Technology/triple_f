<?php

namespace App\Observers;

use App\Models\ContactUs;
use App\Models\User;
use Filament\Notifications\Notification;

class ContactUsObserver
{
    /**
     * Handle the ContactUs "created" event.
     */
    public function created(ContactUs $contactUs): void
    {
        $admins = User::query()->where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Notification::make()
                ->title('New Contact us request from : ' . $contactUs->email)
                ->sendToDatabase($admin);
        }

    }

    /**
     * Handle the ContactUs "updated" event.
     */
    public function updated(ContactUs $contactUs): void
    {
        //
    }

    /**
     * Handle the ContactUs "deleted" event.
     */
    public function deleted(ContactUs $contactUs): void
    {
        //
    }

    /**
     * Handle the ContactUs "restored" event.
     */
    public function restored(ContactUs $contactUs): void
    {
        //
    }

    /**
     * Handle the ContactUs "force deleted" event.
     */
    public function forceDeleted(ContactUs $contactUs): void
    {
        //
    }
}
