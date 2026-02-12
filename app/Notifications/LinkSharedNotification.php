<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkSharedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected User $actor,
        protected Link $link,
        protected string $permission
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'link_shared',
            'actor' => $this->actor->name,
            'link_id' => $this->link->id,
            'link_title' => $this->link->title,
            'permission' => $this->permission,
            'message' => sprintf(
                '%s a partage le lien "%s" avec vous (%s).',
                $this->actor->name,
                $this->link->title,
                $this->permission
            ),
        ];
    }
}

