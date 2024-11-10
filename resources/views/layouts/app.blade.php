<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Event Management') }} - @yield('title')</title>

    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://kit.fontawesome.com/5d9709df8c.js" crossorigin="anonymous"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <!-- Left side of Navbar -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-semibold text-indigo-600">
                        Event Management
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Events Page Button -->
                        {{-- <a href="{{ route('events.index') }}"
                            class="{{ request()->routeIs('events.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-indigo-500 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2">
                            Events
                        </a> --}}

                        <!-- Registered Events Page Button -->
                        {{-- <a href="{{ route('events.index') }}"
                            class="{{ request()->routeIs('events.registered') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-indigo-500 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2">
                            Registered Events
                        </a> --}}

                        <!-- Create Event Button (Only for Admins) -->
                        @auth
                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('events.create') }}"
                                    class="{{ request()->routeIs('events.create') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-indigo-500 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2">
                                    Create Event
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right side of Navbar (Authentication Links) -->
                <div class="flex items-center">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="text-gray-500 hover:text-gray-700 inline-flex items-center px-3 py-2 text-sm font-medium">
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-gray-500 hover:text-gray-700 inline-flex items-center px-3 py-2 text-sm font-medium">
                                Register
                            </a>
                        @endif
                    @else
                        <div x-data="{ open: false }" class="relative">
                            <!-- Dropdown Heading (User Name) -->
                            <button @click="open = !open" class="text-gray-900 font-medium px-3 py-2 flex items-center">
                                {{ Auth::user()->name }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md overflow-hidden z-10">
                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </div>

                            <!-- Hidden Logout Form -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>

                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-all duration-500"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-all duration-500"
                role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>
</body>

</html>
