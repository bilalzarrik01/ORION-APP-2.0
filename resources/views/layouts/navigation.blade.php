<nav x-data="{ open: false }" class="py-6">
    <div class="flex items-center justify-between">
        <a class="brand" href="{{ route('dashboard') }}">
            <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="Odin logo">
            Orion
        </a>

        <div class="hidden items-center gap-4 md:flex">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                {{ __('Categories') }}
            </x-nav-link>
            <x-nav-link :href="route('links.index')" :active="request()->routeIs('links.*')">
                {{ __('Links') }}
            </x-nav-link>
            @if (Auth::user()->isAdmin())
                <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                    {{ __('Activity Logs') }}
                </x-nav-link>
            @endif
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="nav-btn">
                        {{ Auth::user()->name }}
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <div class="md:hidden">
            <button @click="open = ! open" class="nav-btn">
                Menu
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="mt-4 space-y-2 panel-soft">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('links.index')" :active="request()->routeIs('links.*')">
                {{ __('Links') }}
            </x-responsive-nav-link>
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                    {{ __('Activity Logs') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
