<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Library</p>
                <h2 class="mt-2 font-display text-4xl">Links</h2>
                <p class="mt-3 max-w-xl text-sm text-muted">
                    Capture your best resources and keep them searchable.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="btn btn-secondary" href="{{ route('categories.index') }}">Manage categories</a>
                <a class="btn btn-primary" href="{{ route('links.create') }}">Add link</a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mt-6 panel-soft text-sm text-muted">
            {{ session('status') }}
        </div>
    @endif

    <form class="mt-6 panel-soft" method="GET" action="{{ route('links.index') }}">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <x-input-label class="text-muted" for="q" value="Search title" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" value="{{ $filters['q'] ?? '' }}" aria-label="Search links by title" autocomplete="off" />
            </div>
            <div>
                <x-input-label class="text-muted" for="category" value="Category" />
                <select id="category" name="category" class="field-input mt-1">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $category->id === (string) ($filters['category'] ?? ''))>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label class="text-muted" for="tag" value="Tag" />
                <select id="tag" name="tag" class="field-input mt-1">
                    <option value="">All</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected((string) $tag->id === (string) ($filters['tag'] ?? ''))>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a class="btn btn-secondary" href="{{ route('links.index') }}">Reset</a>
        </div>
    </form>

    @php
        $activeFilters = [];
        $searchValue = $filters['q'] ?? null;
        $categoryId = $filters['category'] ?? null;
        $tagId = $filters['tag'] ?? null;

        if ($searchValue) {
            $activeFilters[] = ['key' => 'q', 'label' => 'Search: ' . $searchValue];
        }

        if ($categoryId) {
            $categoryLabel = $categories->firstWhere('id', (int) $categoryId)?->name;
            if ($categoryLabel) {
                $activeFilters[] = ['key' => 'category', 'label' => 'Category: ' . $categoryLabel];
            }
        }

        if ($tagId) {
            $tagLabel = $tags->firstWhere('id', (int) $tagId)?->name;
            if ($tagLabel) {
                $activeFilters[] = ['key' => 'tag', 'label' => 'Tag: ' . $tagLabel];
            }
        }
    @endphp

    @if ($activeFilters)
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($activeFilters as $filter)
                @php
                    $query = request()->query();
                    unset($query[$filter['key']]);
                @endphp
                <a class="badge" href="{{ route('links.index', $query) }}">
                    {{ $filter['label'] }} ×
                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-8 panel">
        @if ($links->isEmpty())
            @if ($activeFilters)
                <p class="text-sm text-muted">No links match your filters.</p>
                <div class="mt-4">
                    <a class="btn btn-secondary" href="{{ route('links.index') }}">Clear filters</a>
                </div>
            @else
                <p class="text-sm text-muted">No links yet. Add your first one.</p>
            @endif
        @else
            <div class="space-y-4">
                @foreach ($links as $link)
                    <div class="panel-soft flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold">{{ $link->title }}</p>
                            <a class="mt-1 block text-xs text-muted underline decoration-white/30" href="{{ $link->url }}" target="_blank" rel="noreferrer">
                                {{ $link->url }}
                            </a>
                            <div class="mt-2 text-xs text-muted">
                                Category: {{ $link->category?->name ?? '—' }}
                            </div>
                            @if ($link->tags->isNotEmpty())
                                <div class="mt-3 badge-row">
                                    @foreach ($link->tags as $tag)
                                        <span class="badge">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a class="btn btn-secondary" href="{{ route('links.edit', $link) }}">Edit</a>
                            <form method="POST" action="{{ route('links.destroy', $link) }}" onsubmit="return confirm('Delete this link?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
