@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Team List -->
    <div class="md:col-span-1 bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Teams</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($teams as $team)
            <a href="#" class="block p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $team->name }}</p>
                        <p class="text-sm text-gray-500">{{ $team->members->count() }} members</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div class="md:col-span-3 bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Team Chat</h2>
        </div>
        <div class="p-4 h-96 overflow-y-auto">
            <!-- Messages will go here -->
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-comments fa-3x mb-2"></i>
                <p>Select a team to start chatting</p>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200">
            <form>
                <div class="flex space-x-2">
                    <input type="text" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Type your message...">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-paper-plane mr-2"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection