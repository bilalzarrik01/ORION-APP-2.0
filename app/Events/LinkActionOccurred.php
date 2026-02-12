<?php

namespace App\Events;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkActionOccurred
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $actor,
        public Link $link,
        public string $action
    ) {
    }
}

