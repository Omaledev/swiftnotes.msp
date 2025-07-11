{{-- @extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Team Contacts</h3>
        <p class="mt-1 text-sm text-gray-500">People you collaborate with across all teams</p>
    </div>
    <ul class="divide-y divide-gray-200">
        @forelse($contacts as $contact)
        <li class="px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($contact->name) }}&background=random" alt="">
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                        <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                        <div class="mt-1 text-xs text-gray-500">
                            Shared teams:
                            @foreach($contact->teams as $team)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-1">
                                    {{ $team->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-envelope mr-1"></i> Message
                    </button>
                </div>
            </div>
        </li>
        @empty
        <li class="px-4 py-12 text-center">
            <i class="fas fa-user-friends text-4xl text-gray-400 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-900">No contacts yet</h4>
            <p class="mt-1 text-sm text-gray-500">Join or create a team to start collaborating with others</p>
        </li>
        @endforelse
    </ul>
</div>
@endsection --}}