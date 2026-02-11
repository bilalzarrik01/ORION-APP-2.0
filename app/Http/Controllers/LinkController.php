<?php

namespace App\Http\Controllers;

use App\Events\LinkActionOccurred;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $filters = [
            'q' => request()->query('q'),
            'category' => request()->query('category'),
            'tag' => request()->query('tag'),
            'favorites' => request()->boolean('favorites'),
        ];

        $links = $this->accessibleLinksQuery($user)
            ->with(['category', 'tags'])
            ->withExists(['favoredBy as is_favorite' => fn ($query) => $query->where('users.id', $user->id)])
            ->when($filters['q'], function ($query, $q) {
                $query->where('title', 'like', '%' . $q . '%');
            })
            ->when($filters['category'], function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($filters['tag'], function ($query, $tagId) {
                $query->whereHas('tags', fn ($tagQuery) => $tagQuery->whereKey($tagId));
            })
            ->when($filters['favorites'], function ($query) use ($user) {
                $query->whereHas('favoredBy', fn ($favQuery) => $favQuery->where('users.id', $user->id));
            })
            ->latest()
            ->get();

        $categories = $this->userCategories($user);
        $tags = Tag::query()
            ->whereHas('links', function ($query) use ($user) {
                if ($user->isAdmin()) {
                    return;
                }

                $query->where(function ($nested) use ($user) {
                    $nested->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('user_id', $user->id))
                        ->orWhereHas('sharedUsers', fn ($sharedQuery) => $sharedQuery->where('users.id', $user->id));
                });
            })
            ->orderBy('name')
            ->get();

        return view('links.index', compact('links', 'categories', 'tags', 'filters'));
    }

    public function create()
    {
        $this->authorize('create', Link::class);
        $categories = $this->userCategories(Auth::user());

        return view('links.create', compact('categories'));
    }

    public function store(StoreLinkRequest $request)
    {
        $validated = $request->validated();

        $link = Link::create([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category_id' => $validated['category_id'],
        ]);

        $link->tags()->sync($this->resolveTagIds($validated['tags'] ?? ''));
        event(new LinkActionOccurred(Auth::user(), $link, 'created'));

        return redirect()
            ->route('links.index')
            ->with('status', 'Link created.');
    }

    public function edit(Link $link)
    {
        $this->authorize('update', $link);

        $user = Auth::user();
        $isOwner = $link->isOwnedBy($user);
        $canShare = $user->can('share', $link);
        $categories = $isOwner ? $this->userCategories($user) : collect();
        $tags = $link->tags->pluck('name')->implode(', ');
        $shares = $canShare ? $link->sharedUsers()->orderBy('name')->get() : collect();
        $users = $canShare
            ? User::query()->whereKeyNot($user->id)->orderBy('name')->get()
            : collect();

        return view('links.edit', compact('link', 'categories', 'tags', 'isOwner', 'canShare', 'shares', 'users'));
    }

    public function update(UpdateLinkRequest $request, Link $link)
    {
        $validated = $request->validated();

        $payload = [
            'title' => $validated['title'],
            'url' => $validated['url'],
        ];
        if (array_key_exists('category_id', $validated)) {
            $payload['category_id'] = $validated['category_id'];
        }

        $link->update($payload);

        $link->tags()->sync($this->resolveTagIds($validated['tags'] ?? ''));
        event(new LinkActionOccurred(Auth::user(), $link, 'updated'));

        return redirect()
            ->route('links.index')
            ->with('status', 'Link updated.');
    }

    public function destroy(Link $link)
    {
        $this->authorize('delete', $link);
        $link->delete();
        event(new LinkActionOccurred(Auth::user(), $link, 'deleted'));

        return redirect()
            ->route('links.index')
            ->with('status', 'Link deleted.');
    }

    private function userCategories(User $user)
    {
        $query = Category::query();
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query
            ->orderBy('name')
            ->get();
    }

    private function accessibleLinksQuery(User $user)
    {
        $query = Link::query();

        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function ($nested) use ($user) {
            $nested->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('user_id', $user->id))
                ->orWhereHas('sharedUsers', fn ($sharedQuery) => $sharedQuery->where('users.id', $user->id));
        });
    }

    private function resolveTagIds(string $rawTags): array
    {
        $names = collect(explode(',', $rawTags))
            ->map(fn ($tag) => trim(mb_strtolower($tag)))
            ->filter()
            ->unique()
            ->values();

        if ($names->isEmpty()) {
            return [];
        }

        return $names->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        })->all();
    }
}
