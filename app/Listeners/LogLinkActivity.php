<?php

namespace App\Listeners;

use App\Events\LinkActionOccurred;
use App\Models\ActivityLog;

class LogLinkActivity
{
    public function handle(LinkActionOccurred $event): void
    {
        $labels = [
            'created' => 'a cree',
            'updated' => 'a modifie',
            'deleted' => 'a supprime',
            'restored' => 'a restaure',
            'force_deleted' => 'a supprime definitivement',
            'shared' => 'a partage',
            'share_permission_updated' => 'a modifie la permission de partage',
            'share_revoked' => 'a retire le partage',
        ];

        $verb = $labels[$event->action] ?? $event->action;
        $date = now()->format('d/m/Y H:i');

        ActivityLog::create([
            'user_id' => $event->actor->id,
            'action' => $event->action,
            'entity_type' => 'link',
            'entity_id' => $event->link->id,
            'message' => sprintf(
                '%s %s le lien %s le %s',
                $event->actor->name,
                $verb,
                $event->link->title,
                $date
            ),
        ]);
    }
}

