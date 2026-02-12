<?php

namespace App\Listeners;

use App\Events\LinkPermissionUpdated;
use App\Notifications\LinkPermissionUpdatedNotification;

class SendLinkPermissionUpdatedNotification
{
    public function handle(LinkPermissionUpdated $event): void
    {
        $event->recipient->notify(
            new LinkPermissionUpdatedNotification(
                $event->actor,
                $event->link,
                $event->oldPermission,
                $event->newPermission
            )
        );
    }
}

