<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;

class LinkPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Link $link): bool
    {
        return $link->isOwnedBy($user) || $link->sharedUsers()->where('users.id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return ! $user->isViewer();
    }

    public function update(User $user, Link $link): bool
    {
        if ($user->isViewer()) {
            return false;
        }

        if ($link->isOwnedBy($user)) {
            return true;
        }

        return $link->sharedUsers()
            ->where('users.id', $user->id)
            ->wherePivot('permission', 'edition')
            ->exists();
    }

    public function delete(User $user, Link $link): bool
    {
        return ! $user->isViewer() && $link->isOwnedBy($user);
    }

    public function restore(User $user, Link $link): bool
    {
        return ! $user->isViewer() && $link->isOwnedBy($user);
    }

    public function forceDelete(User $user, Link $link): bool
    {
        return false;
    }

    public function share(User $user, Link $link): bool
    {
        return ! $user->isViewer() && $link->isOwnedBy($user);
    }
}

