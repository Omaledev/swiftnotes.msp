<header class="bg-blue-600 text-white shadow-md">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-sticky-note text-2xl"></i>
                <h1 class="text-2xl font-bold">SWIFTNOTES</h1>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <span class="font-medium">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>
