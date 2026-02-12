<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
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

    public function view(User $user, Category $category): bool
    {
        return (int) $category->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return ! $user->isViewer();
    }

    public function update(User $user, Category $category): bool
    {
        return ! $user->isViewer() && (int) $category->user_id === (int) $user->id;
    }

    public function delete(User $user, Category $category): bool
    {
        return ! $user->isViewer() && (int) $category->user_id === (int) $user->id;
    }

    public function restore(User $user, Category $category): bool
    {
        return ! $user->isViewer() && (int) $category->user_id === (int) $user->id;
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}

