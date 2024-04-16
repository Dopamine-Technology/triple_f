<?php

namespace App\Traits;
trait NotifyChecker
{
    public function notificationPermissionsCheck($user, $notification_type): bool
    {
        if (empty($notification_type)) {
            return true;
      }
        return in_array($notification_type, $user->notification_settings);

    }
}
