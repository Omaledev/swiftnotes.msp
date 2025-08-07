<nav class="bg-blue-600 border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <defs>
                    <linearGradient id="iconGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#7c3aed" />
                        <stop offset="100%" stop-color="#a855f7" />
                    </linearGradient>
                </defs>
                <path fill="url(#iconGradient)"
                    d="M19 4h-2a1 1 0 0 0 0 2h2v12h-7.59l-3.7 3.71A1 1 0 0 1 8 22a.92.92 0 0 1-.38-.08A1 1 0 0 1 7 21V18H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1Z" />
                <path fill="#fff" d="M6 7h8v1H6zm0 3h8v1H6zm0 3h5v1H6z" />
            </svg>
            <span class="self-center text-xl font-semibold whitespace-nowrap text-white">SWIFT<span class="text-amber-300">NOTE</span></span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex md:items-center md:space-x-6">
            <!-- Menu Items -->
            <ul class="flex space-x-6 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" class="block py-2 px-0 text-white hover:text-amber-300 transition-colors">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-0 text-white hover:text-amber-300 transition-colors">Chats</a>
                </li>
                <li>
                    <a href="{{ route('pages.collaborators') }}" class="block py-2 px-0 text-white hover:text-amber-300 transition-colors">Collaborators</a>
                </li>
                <li>
                    <a href="{{ route('pages.settings') }}" class="block py-2 px-0 text-white hover:text-amber-300 transition-colors">Setting</a>
                </li>
            </ul>

            <!-- Welcome message and Search -->
            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-sm font-bold text-amber-300 whitespace-nowrap">
                        Hi, {{ Auth::user()->name }}
                    </span>
                @endauth

                <!-- Search bar -->
                <form action="{{ route('search') }}" method="GET" class="relative w-48">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" name="query" id="search-navbar"
                        class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search notes and teams..."
                        value="{{ request('query') }}"
                        x-data
                        @keyup.enter="window.location.href = '{{ route('search') }}?query=' + encodeURIComponent($event.target.value)">
                </form>

                <!-- Logout (Desktop) -->
                @auth
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="text-sm text-white hover:text-amber-300 transition-colors cursor-pointer">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>

        <!-- Mobile Controls -->
        <div class="flex items-center md:hidden">
            
            <!-- Welcome message -->
             @auth
                <span class="text-sm font-bold text-amber-300 truncate max-w-[100px] mr-2">
                    Hi, {{ Str::limit(Auth::user()->name, 8, '...') }}
                </span>
            @endauth

            <!-- Button container that won't wrap -->
            <div class="flex-shrink-0 flex items-center space-x-1">
                <!-- Search button -->
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false"
                    class="text-gray-200 hover:bg-blue-700 rounded-lg p-2">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>

                <!-- Mobile menu button -->
                <button data-collapse-toggle="mobile-menu" type="button"
                    class="text-white hover:bg-blue-700 rounded-lg p-2"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile search - Toggleable -->
        <div class="hidden w-full md:hidden mt-2" id="navbar-search">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="query" id="search-navbar-mobile"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search notes and teams..."
                    value="{{ request('query') }}">
            </form>
        </div>

        <!-- Mobile menu -->
        <div class="hidden w-full md:hidden" id="mobile-menu">
            <ul class="flex flex-col font-medium mt-4 rounded-lg bg-blue-700">
                <li>
                    <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-white hover:bg-amber-300 rounded">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 px-4 text-white hover:bg-amber-300 rounded">Chats</a>
                </li>
                <li>
                    <a href="{{ route('pages.collaborators') }}" class="block py-2 px-4 text-white hover:bg-amber-300 rounded">Collaborators</a>
                </li>
                <li>
                    <a href="{{ route('pages.settings') }}" class="block py-2 px-4 text-white hover:bg-amber-300 rounded">Setting</a>
                </li>
                @auth
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 px-4 text-white hover:bg-amber-300 rounded">
                            Logout
                        </button>
                    </form>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
