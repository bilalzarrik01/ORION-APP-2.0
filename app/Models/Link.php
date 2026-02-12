<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function owner(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Category::class,
            'id',
            'id',
            'category_id',
            'user_id'
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'link_user')
            ->withPivot('permission')
            ->withTimestamps();
    }

    public function favoredBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isOwnedBy(User $user): bool
    {
        if ($this->relationLoaded('category') && $this->category) {
            return (int) $this->category->user_id === (int) $user->id;
        }

        return $this->category()->where('user_id', $user->id)->exists();
    }

    public function sharedPermissionFor(User $user): ?string
    {
        $pivot = $this->sharedUsers()
            ->where('users.id', $user->id)
            ->first()?->pivot;

        return $pivot?->permission;
    }
}
