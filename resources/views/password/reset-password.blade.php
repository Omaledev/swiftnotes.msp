<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <div class="min-h-screen flex items-center justify-center p-3">
        <div class="w-full max-w-xs mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4">
                
                <div class="text-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Reset Password</h2>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="{{ $email ?? old('email') }}" required autofocus>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-8"
                                required>
                            <span data-input-id="password"
                                class="toggle-password absolute inset-y-0 right-0 pr-2 flex items-center cursor-pointer text-xs">
                                👁️
                            </span>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                            Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 pr-8"
                                required>
                            <span data-input-id="password_confirmation"
                                class="toggle-password absolute inset-y-0 right-0 pr-2 flex items-center cursor-pointer text-xs">
                                👁️
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
