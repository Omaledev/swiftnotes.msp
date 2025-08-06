@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
                <p class="mt-2 text-gray-600">Manage your profile and account preferences</p>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Settings Sections -->
            <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                <!-- Profile Section -->
                <div class="px-6 py-5">
                    <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information</p>

                    <form method="POST" action="{{ route('settings.name.update') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Email Section -->
                {{-- <div class="px-6 py-5">
                    <h2 class="text-lg font-medium text-gray-900">Email Address</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your account's email address</p>

                    <form method="POST" action="{{ route('settings.email.update') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <div class="mt-1">
                                <input type="password" name="current_password" id="current_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                Update Email
                            </button>
                        </div>
                    </form>
                </div> --}}
                <div class="px-6 py-5">
                    <h2 class="text-lg font-medium text-gray-900">Email Address</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Current email: {{ auth()->user()->email }}
                    </p>

                    <form method="POST" action="{{ route('settings.email.update') }}" class="mt-6 space-y-6" onsubmit="return validateEmailChange()">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">New Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1">
                                <input type="password" name="current_password" id="current_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                Update Email
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Section -->
                <div class="px-6 py-5">
                    <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure</p>

                    <form method="POST" action="{{ route('settings.password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 cursor-pointer">Current Password</label>
                            <div class="mt-1">
                                <input type="password" name="current_password" id="current_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 cursor-pointer">New Password</label>
                            <div class="mt-1">
                                <input type="password" name="new_password" id="new_password" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 cursor-pointer">Confirm Password</label>
                            <div class="mt-1">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Teams Section -->
                <div class="px-6 py-5">
                    <!-- Owned Teams -->
                    @if($ownedTeams->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900">Teams You Own</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage the teams you created</p>

                        <div class="mt-6 space-y-4">
                            @foreach($ownedTeams as $team)
                            <div class="flex items-center space-x-3">
                                <form method="POST" action="{{ route('settings.team.update', $team->id) }}" class="flex-grow">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $team->name }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </form>
                                <button type="submit" form="update-team-{{ $team->id }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                    Update
                                </button>
                                <form method="POST" action="{{ route('settings.team.delete', $team->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this team?')"
                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Joined Teams -->
                    @if($joinedTeams->count() > 0)
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Teams You've Joined</h2>
                        <p class="mt-1 text-sm text-gray-500">Teams you're a member of</p>

                        <div class="mt-6 space-y-4">
                            @foreach($joinedTeams as $team)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $team->name }}</h3>
                                    <p class="text-xs text-gray-500">Owner: {{ $team->owner->name }}</p>
                                </div>
                                <form method="POST" action="{{ route('settings.team.leave', $team->id) }}">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure you want to leave this team?')"
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                        Exit Team
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Delete Account Section -->
                <div class="px-6 py-5 bg-red-50 rounded-b-lg">
                    <h2 class="text-lg font-medium text-red-800 cursor-pointer">Delete Account</h2>
                    <p class="mt-1 text-sm text-red-600">Once your account is deleted, all of its resources and data will be permanently deleted</p>

                    <form method="POST" action="{{ route('settings.account.delete') }}" class="mt-6" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                        @csrf
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-red-700 cursor-pointer">Password</label>
                            <div class="mt-1">
                                <input type="password" name="confirm_password" id="confirm_password" required
                                    class="block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <script>
        function notifications() {

            // Show a nicer notification
            const notification = document.createElement('div');
            notification.className =
                'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center';
            notification.innerHTML = `
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        function validateEmailChange() {
            const currentEmail = "{{ auth()->user()->email }}";
            const newEmail = document.getElementById('email').value;

            if (currentEmail.toLowerCase() === newEmail.toLowerCase()) {
                alert('You entered the same email address. No changes were made.');
                return false;
            }

            return true;
        }

    </script>
@endsection
