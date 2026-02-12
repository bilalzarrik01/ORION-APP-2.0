<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-muted">Admin</p>
            <h2 class="mt-2 font-display text-4xl">Activity logs</h2>
        </div>
    </x-slot>

    <div class="mt-8 panel">
        <div class="space-y-3">
            @forelse ($logs as $log)
                <div class="panel-soft">
                    <p class="text-sm font-semibold">{{ $log->message }}</p>
                    <p class="text-xs text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                </div>
            @empty
                <p class="text-sm text-muted">No activity yet.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>

