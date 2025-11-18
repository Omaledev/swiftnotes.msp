@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section  -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    {{ $note->title }}
                    @if ($note->activeEditors->count())
                        <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-pencil-alt"></i> Editing
                        </span>
                    @endif
                </h1>
                <div class="flex items-center gap-2 mt-2 text-sm text-gray-600">
                    <span>Created by: {{ $note->creator->name }}</span>
                    <span>•</span>
                    <span>{{ $note->created_at->format('M d, Y H:i') }}</span>
                    @if ($note->updated_at->ne($note->created_at))
                        <span>•</span>
                        <span>Last updated: {{ $note->updated_at->format('M d, Y H:i') }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('notes.index', $team) }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Back to Notes
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Flex container - column on mobile, row on desktop -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

            <!-- Note Content with beautiful typography -->
            <div class="prose max-w-none w-full md:flex-grow">
                <div class="text-[17px] leading-[1.7] tracking-wide font-medium antialiased"
                    style="font-family: 'Manrope', sans-serif; color: #1f2937;">
                    {!! nl2br(e($note->content)) !!}
                </div>
            </div>

            <!-- Action Buttons - Right-aligned on mobile, original position on desktop -->
            @if ($note->team->members->contains(Auth::user()))
                <div class="flex justify-end space-x-3 md:ml-4 mt-3 md:mt-0">
                    <button onclick="toggleEditForm()"
                        class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors flex items-center gap-2 shadow-sm cursor-pointer text-sm"
                        title="Edit Note">
                        <i class="fas fa-edit"></i>
                        <span class="font-medium">Edit</span>
                    </button>
                    @if ($canDelete)
                        <button onclick="confirmDelete()"
                            class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors flex items-center gap-2 shadow-sm cursor-pointer text-sm"
                            title="Delete Note">
                            <i class="fas fa-trash"></i>
                            <span class="font-medium">Delete</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Section (hidden by default) -->
    @if ($note->team->members->contains(Auth::user()))
            <div id="editFormContainer" class="bg-white rounded-xl shadow-md overflow-hidden hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Note
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('notes.update', $note) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                            <input type="text" name="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $note->title }}" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Content</label>
                            <textarea name="content"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                rows="8" required>{{ $note->content }}</textarea>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="toggleEditForm()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors cursor-pointer">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
            <!-- Centering Wrapper -->
            <div class="flex items-center justify-center w-full h-full">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Note</h3>
                        <p class="text-gray-600 mb-6">Are you sure you want to delete this note?</p>
                        <div class="flex justify-end space-x-3">
                            <button onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors cursor-pointer">
                                Cancel
                            </button>
                            <form id="deleteForm" action="{{ route('notes.destroy', $note) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @push('scripts')
        <script>
            function toggleEditForm() {
                document.getElementById('editFormContainer').classList.toggle('hidden');
            }

            function confirmDelete() {
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            // Close modal when clicking outside
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });

            // Close with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDeleteModal();
            });

            // Real-time editing scripts (unchanged)
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.querySelector('textarea[name="content"]');
                if (textarea) {
                    textarea.addEventListener('focus', function() {
                        fetch(`/notes/{{ $note->id }}/start-editing`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/x-www-form-urlencoded',
                            }
                        });
                    });

                    window.addEventListener('beforeunload', function() {
                        fetch(`/notes/{{ $note->id }}/stop-editing`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            keepalive: true
                        });
                    });
                }
            });

        document.addEventListener('DOMContentLoaded', async function() {
        // Wait for the module to load
        if (typeof initializeRealTimeFeatures === 'function') {
            await initializeRealTimeFeatures({{ $note->team->id }}, {{ $note->id }});
        } else {
            // Fallback: retry after a delay
            setTimeout(async () => {
                if (typeof initializeRealTimeFeatures === 'function') {
                    await initializeRealTimeFeatures({{ $note->team->id }}, {{ $note->id }});
                }
            }, 1000);
        }

        // Store current user data
        window.user = @json(auth()->user());

        // Track editing status for this note
        const textarea = document.querySelector('textarea[name="content"]');
        if (textarea) {
            textarea.addEventListener('focus', () => {
                axios.post(`/notes/{{ $note->id }}/start-editing`);
            });

            textarea.addEventListener('blur', () => {
                axios.post(`/notes/{{ $note->id }}/stop-editing`);
            });
        }
    });
        </script>
    @endpush
@endsection
