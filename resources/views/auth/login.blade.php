<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Prevent horizontal scrolling on any device */
        html,
        body {
            width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>

<body>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- Smooth blue gradient that matches your color scheme -->
    <div class="min-h-screen flex items-center justify-center p-3"
        style="background-image: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80'); background-size: cover; background-position: center;">

        <!-- Tight container for 320px devices -->
        <div class="w-full max-w-[280px] sm:max-w-xs md:max-w-sm mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="px-3 py-4 sm:px-4 sm:py-6">

                    <h1
                        class="text-center text-gray-600 font-bold tracking-wider text-xl sm:text-2xl md:text-3xl uppercase mb-2 sm:mb-3 transform transition-all hover:scale-105">
                        SWIFT<span class="text-amber-300">NOTES</span>
                    </h1>
                    <!-- Compact divider -->
                    <div class="flex items-center justify-center mb-4 sm:mb-5">
                        <div class="flex-1 border-t border-gray-300"></div>
                        <span class="px-2 text-gray-600 font-medium text-xs sm:text-sm">Login</span>
                        <div class="flex-1 border-t border-gray-300"></div>
                    </div>

                    <!-- Form fields -->
                    <div class="space-y-3">

                        <!-- Email field -->
                        <div>
                            <label for="email"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                placeholder="johndoe@gmail.com" required />
                            @error('email')
                                <p class="mt-1 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password field -->
                        <div>
                            <label for="password"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 pr-8"
                                    placeholder="••••••••" required />
                                <span data-input-id="password"
                                    class=" toggle-password absolute inset-y-0 right-0 pr-2 flex items-center cursor-pointer text-xs">
                                    👁️
                                </span>
                            </div>
                            @error('password')
                                <p class="mt-1 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-1 text-right">
                                <a href="{{ route('password.request') }}"
                                    class="text-[11px] sm:text-xs text-blue-600 hover:text-blue-800">Forgot
                                    Password?</a>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="pt-1">
                            <button type="submit"
                                class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 text-xs sm:text-sm cursor-pointer">
                                Login
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- registration link -->
            <div class="px-3 py-3 bg-gray-50 border-t border-gray-200 text-center">
                <p class="text-[11px] sm:text-xs text-gray-600">
                    Don't have an account? <a href="{{ route('register') }}"
                        class="font-medium text-blue-600 hover:text-blue-800">Register</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
