@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
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
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <span>Search</span>
                    </button>
                </form>

                <button
                    class="w-full md:w-auto px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center md:justify-start gap-2 cursor-pointer"
                    data-bs-toggle="modal" data-bs-target="#createNoteModal">
                    <i class="fas fa-plus"></i> Create Note
                </button>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6 flex items-center gap-2">
            @if ($isOwner)
                <span
                    class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium flex items-center gap-1">
                    <i class="fas fa-crown"></i> Team Owner
                </span>
            @else
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium flex items-center gap-1">
                    <i class="fas fa-user"></i> Team Member
                </span>
            @endif
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                <i class="fas fa-users"></i> {{ $memberCount }} Members
            </span>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
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
                                                class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50">
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
            <div class="text-center py-12">
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
            </div>
            <div class="mt-6">
                {{ $notes->links() }}
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
@endsection
