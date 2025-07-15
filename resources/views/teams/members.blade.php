@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">{{ $team->name }} Members</h1>
            <a href="{{ route('notes.index', $team) }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Back to Notes
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach ($members as $member)
                    <li class="p-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-4">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->pivot->role }}</p>
                            </div>
                        </div>
                        @if ($team->created_by === Auth::id() && $member->id !== Auth::id())
                            <form action="{{ route('teams.members.remove', [$team, $member]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to remove this member?')"
                                        class="px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 cursor-pointer">
                                    Remove
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </div>
@endsection
