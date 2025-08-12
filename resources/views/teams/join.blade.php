@extends('layouts.app')

@section('title', 'Join Team')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Join a Team</h1>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('teams.join.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="inviteCode" class="block text-sm font-medium text-gray-700">Invite Code</label>
                <input type="text" id="inviteCode" name="invite_code" required
                    placeholder="Enter team invite code"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 cursor-pointer">
                    Join Team
                </button>
            </div>
        </form>
    </div>
@endsection
