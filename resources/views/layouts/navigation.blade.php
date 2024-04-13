<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center py-2">
                    <a href="{{ route('reward.index') }}" class="block">
                        <x-application-logo class="h-[80px]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- <x-nav-link :href="route('reward.index')" :active="!request()->routeIs(['spinconfig.index', 'voucher.index', 'errorlogs.index'])"> --}}
                    <x-nav-link :href="route('reward.index')" :active="!collect(['/system/voucher', '/system/spin-config', '/system/error-logs'])->contains(
                        function ($url) {
                            return Str::startsWith(request()->url(), url($url));
                        },
                    )">
                        {{ __('Rewards & Delivery') }}
                    </x-nav-link>
                    <x-nav-link :href="route('voucher.index')" :active="Str::startsWith(request()->url(), url('/system/voucher'))">
                        {{ __('Voucher Generate') }}
                    </x-nav-link>
                    <x-nav-link :href="route('spinconfig.index')" :active="request()->routeIs('spinconfig.index')">
                        {{ __('Spin Configuration') }}
                    </x-nav-link>
                    <x-nav-link :href="route('errorlogs.index')" :active="request()->routeIs('errorlogs.index')">
                        {{ __('User Error Logs') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col items-start mr-2">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="text-xs font-light">{{ Auth::user()->username }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Auth::user()->level === 10)
                            <x-dropdown-link :href="route('user.management')">
                                {{ __('Admin Management') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('setting.index')">
                                {{ __('Setting Apps') }}
                            </x-dropdown-link>
                        @endif
                        <!-- Authentication -->
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
        </div>
    </div>
</nav>
