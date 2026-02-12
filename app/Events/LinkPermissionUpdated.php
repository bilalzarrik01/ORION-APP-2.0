<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkPermissionUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $actor,
        public User $recipient,
        public Link $link,
        public string $oldPermission,
        public string $newPermission
    ) {
    }
}

