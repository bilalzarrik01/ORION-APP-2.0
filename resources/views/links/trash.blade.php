<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Library</p>
                <h2 class="mt-2 font-display text-4xl">Trash</h2>
            </div>
            <a class="btn btn-secondary" href="{{ route('links.index') }}">Back to links</a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="mt-6 panel-soft text-sm text-muted">{{ session('status') }}</div>
    @endif

    <div class="mt-8 panel">
        @if ($links->isEmpty())
            <p class="text-sm text-muted">Trash is empty.</p>
        @else
            <div class="space-y-4">
                @foreach ($links as $link)
                    <div class="panel-soft flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold">{{ $link->title }}</p>
                            <p class="text-xs text-muted">Deleted {{ $link->deleted_at?->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2">
                            @can('restore', $link)
                                <form method="POST" action="{{ route('links.restore', $link->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">Restore</button>
                                </form>
                            @endcan
                            @can('forceDelete', $link)
                                <form method="POST" action="{{ route('links.force-delete', $link->id) }}" onsubmit="return confirm('Delete permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary">Delete permanently</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

