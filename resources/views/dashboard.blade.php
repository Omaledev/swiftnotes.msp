@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex flex-col space-y-6">

        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex space-x-4">
            <a href="{{ route('teams.create') }}"
            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Create a New Team
            </a>
            <a href="{{ route('teams.join') }}"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-user-plus mr-2"></i> Join a New Team
            </a>
        </div>

        <!-- Teams Sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Owned Teams -->
            @if($ownedTeams->count())
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4 text-blue-600">
                        <i class="fas fa-crown mr-2"></i> Your Teams (Owned)
                    </h2>
                    <div class="space-y-3">
                        @foreach($ownedTeams as $team)
                            <a href="{{ route('notes.index', $team) }}"
                            class="block p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-medium">{{ $team->name }}</h3>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Owner</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Joined Teams -->
            @if($joinedTeams->count())
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4 text-green-600">
                        <i class="fas fa-users mr-2"></i> Joined Teams
                    </h2>
                    <div class="space-y-3">
                        @foreach($joinedTeams as $team)
                            <a href="{{ route('notes.index', $team) }}"
                            class="block p-4 border border-green-200 rounded-lg hover:bg-green-50 transition">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-medium">{{ $team->name }}</h3>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Member</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Owner: {{ $team->owner->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Empty State -->
        @if($ownedTeams->isEmpty() && $joinedTeams->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow text-center">
                <i class="fas fa-sticky-note text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No teams yet</h3>
                <p class="text-gray-500 mt-2">Get started by creating a new team or joining an existing one.</p>
                <div class="mt-6 flex justify-center space-x-4">
                    <button onclick="window.location.href='{{ route('teams.create') }}'" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Create Team
                    </button>
                    <a href="{{ route('teams.join') }}" class="btn btn-success">
                        <i class="fas fa-user-plus mr-2"></i> Join Team
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
