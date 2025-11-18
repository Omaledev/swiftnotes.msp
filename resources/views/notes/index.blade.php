@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
         <!-- Team Invite Code Section (show to team members) -->
        @if($team)
            <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            Your team invite code:
                        </p>
                        <div class="mt-4">
                            <p class="text-sm font-bold text-green-800">Invite Code:</p>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" value="{{ $team->invite_code }}" id="inviteCode"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    readonly>
                                <button onclick="copyInviteCode()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                    Copy
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-green-700">Share this code with team members so they can join.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $team->name }} Notes</h1>
                <p class="text-gray-600 mt-1">Collaborate with your team in real-time</p>
            </div>

            <!-- Search and Create -->
            <div class="w-full md:w-auto space-y-3">
                <form action="{{ route('notes.index', $team) }}" method="GET" class="flex gap-2">
                    <input type="text" name="search"
                        class="flex-grow px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search notes..." value="{{ request('search') }}">

                    <button type="submit" aria-label="Search notes"
                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg cursor-pointer">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <span class="hidden xs:inline">Search</span>
                    </button>
                </form>

                <button
                    class="w-full md:w-auto px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center md:justify-start gap-2 cursor-pointer"
                    data-bs-toggle="modal" data-bs-target="#createNoteModal">
                    <i class="fas fa-plus"></i> Create Note
                </button>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-2">
            @if ($isOwner)
                <span class="px-1 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 whitespace-nowrap">
                    <i class="fas fa-crown text-xs sm:text-sm"></i> <span>Team Owner</span>
                </span>
            @else
                <span class="px-1 py-1 bg-gray-100 text-gray-800 rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 whitespace-nowrap">
                    <i class="fas fa-user text-xs sm:text-sm"></i> <span>Team Member</span>
                </span>
            @endif

            <!-- New dropdown for member list with online status -->
            <div class="relative group">
                <a href="{{ route('teams.members', $team) }}"
                    class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-medium hover:bg-blue-200 flex items-center gap-1 whitespace-nowrap">
                    <i class="fas fa-users text-xs sm:text-sm"></i>
                    <span>{{ $memberCount }} Members</span>
                </a>

                <!-- Dropdown with member status -->
                <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg p-3 min-w-[200px] z-10 mt-1 border border-gray-200">
                    <h4 class="font-medium text-gray-800 mb-2">Team Members</h4>
                    @foreach($team->members as $member)
                    <div class="flex items-center mb-2" data-user-id="{{ $member->id }}">
                        <span class="user-status inline-block w-2 h-2 rounded-full mr-2 {{ $member->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        <span>{{ $member->name }}</span>
                        @if($member->id === $team->created_by)
                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">Owner</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- End of dropdown -->

            @if ($isOwner)
                <form action="{{ route('teams.destroy', $team) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('WARNING: This will permanently delete the team and ALL its notes. Continue?')"
                            class="px-1 py-1 bg-red-600 text-white rounded-full text-xs sm:text-sm font-medium hover:bg-red-700 flex items-center gap-1 whitespace-nowrap cursor-pointer">
                        <i class="fas fa-trash-alt text-xs sm:text-sm"></i><span>Delete Team</span>
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
            @endif


            @if(request('search'))
                <div class="mb-4 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <p class="text-sm font-medium text-blue-800">
                        Showing results for: "<span class="font-bold">{{ request('search') }}</span>"
                        <a href="{{ route('notes.index', $team) }}" class="text-blue-600 hover:text-blue-800 ml-2">
                            (Clear search)
                        </a>
                    </p>
                </div>
            @endif

        <!-- Notes Grid -->
        @if ($notes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($notes as $note)
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">

                                <a href="{{ route('notes.show', $note) }}" class="text-decoration-none flex-grow-1">
                                    <h3
                                        class="text-xl font-semibold text-gray-800 truncate hover:text-blue-600 transition-colors duration-200">
                                        {{ $note->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($note->content, 150) }}</p>
                                </a>
                                <span class="text-xs text-gray-500">{{ $note->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        {{ substr($note->creator->name, 0, 1) }}
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $note->creator->name }}</span>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('notes.show', $note) }}"
                                        class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($note->created_by == Auth::id() || $isOwner)
                                        <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this note?')"
                                                class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50 cursor-pointer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if ($note->activeEditors->count())
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center text-sm text-yellow-600">
                                        <i class="fas fa-pencil-alt mr-1"></i>
                                        <span>Editing now: {{ $note->activeEditors->pluck('name')->join(', ') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                    <i class="fas fa-sticky-note text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No notes yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first team note.</p>
                <div class="mt-6">
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer"
                        data-bs-toggle="modal" data-bs-target="#createNoteModal">
                        Create Note
                    </button>
                </div>
            </div> --}}

             <!-- Empty State Section -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                    <i class="fas fa-sticky-note text-6xl"></i>
                </div>
                @if(request('search'))
                    <h3 class="text-lg font-medium text-gray-900">No notes found for "{{ request('search') }}"</h3>
                    <p class="mt-1 text-sm text-gray-500">Try a different search term.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900">No notes yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first team note.</p>
                @endif
                <div class="mt-6">
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer"
                        data-bs-toggle="modal" data-bs-target="#createNoteModal">
                        Create Note
                    </button>
                </div>
            </div>

            {{-- <div class="mt-6">
                {{ $notes->links() }}
            </div> --}}
            <div class="mt-6">
                {{ $notes->appends(['search' => request('search')])->links() }}
            </div>
        @endif


        <!-- Create Note Modal -->
        <div class="modal fade my-5" id="createNoteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-xl">
                    <div
                        class="modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-t-lg flex items-center justify-between">
                        <h5 class="modal-title font-bold text-center w-full">Create New Note</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('notes.store', $team) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                                <input type="text" name="title"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                                <textarea name="content"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    rows="8" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-gray-50 p-4 rounded-b-lg flex justify-between">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors cursor-pointer"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">Save
                                Note</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyInviteCode() {
            const inviteCode = document.getElementById('inviteCode');
            inviteCode.select();
            document.execCommand('copy');

            // Show a notification
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center';
            notification.innerHTML = `
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Invite code copied!
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        // for real-time event
         document.addEventListener('DOMContentLoaded', async function() {
        // Wait for the module to load
        if (typeof initializeRealTimeFeatures === 'function') {
            await initializeRealTimeFeatures({{ $team->id }});
        } else {
            // Fallback: retry after a delay
            setTimeout(async () => {
                if (typeof initializeRealTimeFeatures === 'function') {
                    await initializeRealTimeFeatures({{ $team->id }});
                }
            }, 1000);
        }

        // Store current user data
        window.user = @json(auth()->user());
    });

    </script>
@endsection
