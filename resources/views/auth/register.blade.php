<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-3 bg-no-repeat bg-cover bg-center">
        <!-- Compact container for 320px devices -->
        <div class="w-full max-w-[280px] sm:max-w-xs md:max-w-sm mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <form id="registrationForm" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="px-3 py-4 sm:px-4 sm:py-6">
                    <h1 class="text-center text-gray-600 font-bold tracking-wider text-xl sm:text-2xl md:text-3xl uppercase mb-2 sm:mb-3 transform transition-all hover:scale-105">
                        SWIFT<span class="text-amber-300">NOTES</span>
                    </h1>
                    <!-- Compact divider -->
                    <div class="flex items-center justify-center mb-4 sm:mb-5">
                        <div class="flex-1 border-t border-gray-300"></div>
                        <span class="px-2 text-gray-600 font-medium text-xs sm:text-sm">Register</span>
                        <div class="flex-1 border-t border-gray-300"></div>
                    </div>

                    <!-- Form fields  -->
                    <div class="space-y-3">

                        <!-- Name field -->
                        <div>
                            <label for="name" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Your name" required />
                        </div>

                        <!-- Email field -->
                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                placeholder="johndoe@gmail.com" required />
                            @error('email')
                                <p class="mt-1 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password field -->
                        <div>
                            <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 pr-8"
                                    placeholder="••••••••" required />
                                <span data-input-id="password"
                                    class=" toggle-password absolute inset-y-0 right-0 pr-2 flex items-center cursor-pointer text-xs">
                                    👁️
                                </span>
                            </div>
                        </div>

                        <!-- Confirm Password field -->
                        <div>
                            <label for="confirm_password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-2 py-1.5 text-xs sm:text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 pr-8"
                                    placeholder="••••••••" required />
                                <span data-input-id="password_confirmation"
                                    class="toggle-password absolute inset-y-0 right-0 pr-2 flex items-center cursor-pointer text-xs">
                                    👁️
                                </span>
                            </div>
                            @error('password')
                              <p class="mt-1 text-[11px] sm:text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 text-xs sm:text-sm cursor-pointer">
                                Register
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- login link -->
            <div class="px-3 py-3 bg-gray-50 border-t border-gray-200 text-center">
                <p class="text-[11px] sm:text-xs text-gray-600">
                    Have an account? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>