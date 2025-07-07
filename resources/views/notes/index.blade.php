@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $team->name }} Notes</h1>
            {{-- <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNoteModal">
                    Create New Note
                </button>
            </div> --}}
        </div>

        <div class="mb-4">
            <form action="{{ route('notes.index', $team) }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search notes..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
                <div>
                    <button class="btn btn-primary mt-6 cursor-pointer" data-bs-toggle="modal" data-bs-target="#createNoteModal">
                        Create New Note
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notes as $note)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('notes.show', $note) }}" class="text-decoration-none flex-grow-1">
                                <h5 class="mb-1">{{ $note->title }}</h5>
                                <small class="text-muted">Created by: {{ $note->creator->name }}</small>
                            </a>
                            <form action="{{ route('notes.destroy', $note) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this note?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-4">
                            No notes found for this team.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Create Note Modal -->
    <div class="modal fade" id="createNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('notes.store', $team) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Content</label>
                            <textarea name="content" class="form-control w-full" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary cursor-pointer">Save Note</button>
                    </div>
                </form>

                <div class="mt-3 text-muted">
                    @if($isOwner)
                        <span class="badge bg-primary">Owner</span>
                    @else
                        <span class="badge bg-secondary">Visitor</span>
                    @endif
                    <span class="ms-2">Total Members: {{ $memberCount }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
