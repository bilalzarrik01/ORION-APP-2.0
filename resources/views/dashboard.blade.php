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
                <button class="btn btn-secondary">
                    Add new link
                </button>
                <button class="btn btn-primary">
                    Create collection
                </button>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Saved this week</p>
                <p class="mt-3 font-display text-4xl">28</p>
                <p class="mt-2 text-sm text-muted">Curated links and documents added.</p>
            </div>
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Active tags</p>
                <p class="mt-3 font-display text-4xl">12</p>
                <p class="mt-2 text-sm text-muted">Research themes shaping your focus.</p>
            </div>
            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Momentum</p>
                <p class="mt-3 font-display text-4xl">4 day</p>
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
                    <button class="btn btn-secondary">
                        View all
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="panel-soft">
                        <p class="text-sm font-semibold">Design systems audit</p>
                        <p class="mt-1 text-xs text-muted">Tagged: UI, research · 2 hours ago</p>
                    </div>
                    <div class="panel-soft">
                        <p class="text-sm font-semibold">SaaS onboarding teardown</p>
                        <p class="mt-1 text-xs text-muted">Tagged: growth · Yesterday</p>
                    </div>
                    <div class="panel-soft">
                        <p class="text-sm font-semibold">Product brief: Odin 2.0</p>
                        <p class="mt-1 text-xs text-muted">Tagged: strategy · 2 days ago</p>
                    </div>
                </div>
            </div>

            <div class="panel">
                <p class="text-xs uppercase tracking-[0.3em] text-muted">Category mix</p>
                <h3 class="mt-2 font-display text-2xl">Focus areas</h3>
                <div class="mt-6 space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-sm font-semibold">
                            <span>Product</span>
                            <span>42%</span>
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
