{{-- @extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">User Profile</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16">
                    <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="">
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900">{{ $user->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="mt-1 text-sm text-gray-500">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-500">Teams Created</h5>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->createdTeams->count() }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-500">Teams Joined</h5>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->teams->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}