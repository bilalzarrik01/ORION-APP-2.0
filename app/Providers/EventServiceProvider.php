<?php

namespace App\Providers;

use App\Events\LinkActionOccurred;
use App\Events\LinkPermissionUpdated;
use App\Events\LinkShared;
use App\Listeners\LogLinkActivity;
use App\Listeners\SendLinkPermissionUpdatedNotification;
use App\Listeners\SendLinkSharedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LinkActionOccurred::class => [
            LogLinkActivity::class,
        ],
        LinkShared::class => [
            SendLinkSharedNotification::class,
        ],
        LinkPermissionUpdated::class => [
            SendLinkPermissionUpdatedNotification::class,
        ],
    ];
}

