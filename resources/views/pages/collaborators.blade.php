@extends('layouts.app')

@section('title', 'Collaborators')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Your Collaborators</h3>
        <p class="mt-1 text-sm text-gray-500">People you work with across all teams</p>
    </div>
    <ul class="divide-y divide-gray-200">
        @forelse($collaborators as $collaborator)
        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between">
                <div class="flex items-center min-w-0">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($collaborator->name) }}&background=random" 
                             alt="{{ $collaborator->name }}">
                    </div>
                    <div class="ml-4 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ $collaborator->name }}</div>
                        <div class="text-sm text-gray-500 truncate">{{ $collaborator->email }}</div>
                        <div class="mt-1 text-xs text-gray-500">
                            @if($collaborator->teams->isNotEmpty())
                                <span class="mr-1">Teams:</span>
                                @foreach($collaborator->teams as $team)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mr-1 mb-1">
                                        {{ $team->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                            {{-- onclick="window.location.href='{{ route('messages.create', ['recipient' => $collaborator->id]) }}'"> --}}
                        <i class="fas fa-envelope mr-1"></i> Message
                    </button>
                </div>
            </div>
        </li>
        @empty
        <li class="px-4 py-12 text-center">
            <i class="fas fa-user-friends text-4xl text-gray-400 mb-4"></i>
            <h4 class="text-lg font-medium text-gray-900">No collaborators yet</h4>
            <p class="mt-1 text-sm text-gray-500">
                <a href="{{ route('teams.create') }}" class="text-indigo-600 hover:text-indigo-800">Create</a> or join a team to start collaborating
            </p>
        </li>
        @endforelse
    </ul>
</div>
@endsection