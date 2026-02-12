<?php

namespace App\Listeners;

use App\Events\LinkShared;
use App\Notifications\LinkSharedNotification;

class SendLinkSharedNotification
{
    public function handle(LinkShared $event): void
    {
        $event->recipient->notify(
            new LinkSharedNotification(
                $event->actor,
                $event->link,
                $event->permission
            )
        );
    }
}

