<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Collections</p>
                <h2 class="mt-2 font-display text-4xl">Categories</h2>
                <p class="mt-3 max-w-xl text-sm text-muted">
                    Organize your links into focused collections.
                </p>
            </div>
            <div>
                <a class="btn btn-primary" href="{{ route('categories.create') }}">Create category</a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mt-6 panel-soft text-sm text-muted">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-8 panel">
        @if ($categories->isEmpty())
            <p class="text-sm text-muted">No categories yet. Create your first one.</p>
        @else
            <div class="space-y-4">
                @foreach ($categories as $category)
                    <div class="panel-soft flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold">{{ $category->name }}</p>
                            <p class="mt-1 text-xs text-muted">{{ $category->links_count }} links</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a class="btn btn-secondary" href="{{ route('categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
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
