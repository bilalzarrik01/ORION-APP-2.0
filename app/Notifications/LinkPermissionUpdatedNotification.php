<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LinkPermissionUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected User $actor,
        protected Link $link,
        protected string $oldPermission,
        protected string $newPermission
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'link_permission_updated',
            'actor' => $this->actor->name,
            'link_id' => $this->link->id,
            'link_title' => $this->link->title,
            'old_permission' => $this->oldPermission,
            'new_permission' => $this->newPermission,
            'message' => sprintf(
                '%s a modifie votre acces au lien "%s" de %s vers %s.',
                $this->actor->name,
                $this->link->title,
                $this->oldPermission,
                $this->newPermission
            ),
        ];
    }
}

