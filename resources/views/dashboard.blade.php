<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Workspace</p>
                <h2 class="mt-2 font-display text-4xl">
                    {{ __('Dashboard') }}
                </h2>
                <p class="mt-3 max-w-xl text-sm text-muted">
                    Your library is growing. Capture highlights, tag insights, and share the best picks.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a class="btn btn-secondary" href="{{ route('links.create') }}">
                    Add new link
                </a>
                <a class="btn btn-primary" href="{{ route('categories.create') }}">
                    Create collection
                </a>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Saved this week</p>
                <p class="mt-3 font-display text-4xl">{{ $linksThisWeek }}</p>
                <p class="mt-2 text-sm text-muted">Curated links and documents added.</p>
            </div>
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Active tags</p>
                <p class="mt-3 font-display text-4xl">{{ $tagsCount }}</p>
                <p class="mt-2 text-sm text-muted">Research themes shaping your focus.</p>
            </div>
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Momentum</p>
                <p class="mt-3 font-display text-4xl">
                    {{ $streakDays }} {{ \Illuminate\Support\Str::plural('day', $streakDays) }}
                </p>
                <p class="mt-2 text-sm text-muted">Streak of daily captures.</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
            <div class="panel">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-muted">Recent activity</p>
                        <h3 class="mt-2 font-display text-2xl">Latest picks</h3>
                    </div>
                    <a class="btn btn-secondary" href="{{ route('links.index') }}">
                        View all
                    </a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recentLinks as $link)
                        <div class="panel-soft">
                            <p class="text-sm font-semibold">{{ $link->title }}</p>
                            <p class="mt-1 text-xs text-muted">
                                @if ($link->tags->isNotEmpty())
                                    Tagged: {{ $link->tags->pluck('name')->implode(', ') }} ·
                                @endif
                                {{ $link->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-muted">No recent activity yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Category mix</p>
                <h3 class="mt-2 font-display text-2xl">Focus areas</h3>
                <div class="mt-6 space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-sm font-semibold">
                            <span>Product</span>
                            <span>432%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-neutral-900">
                            <div class="h-2 w-[42%] rounded-full bg-white"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm font-semibold">
                            <span>Design</span>
                            <span>31%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-neutral-900">
                            <div class="h-2 w-[31%] rounded-full bg-neutral-400"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm font-semibold">
                            <span>Research</span>
                            <span>27%</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-neutral-900">
                            <div class="h-2 w-[27%] rounded-full bg-neutral-600"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 panel-soft">
                    <p class="text-xs uppercase tracking-[0.3em] text-muted">Quick note</p>
                    <p class="mt-2 text-sm text-muted">
                        You saved 8 more items this week than last week. Keep the momentum going.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

