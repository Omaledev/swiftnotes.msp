@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $note->title }}</h1>
            <a href="{{ route('notes.index', $team) }}" class="btn btn-outline-secondary">Back to Notes</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                @if($note->activeEditors->count())
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-pencil-alt me-2"></i>
                        Currently being edited by: {{ $note->activeEditors->pluck('name')->join(', ') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-3">
                    <small class="text-muted">
                        Created by: {{ $note->creator->name }} •
                        {{ $note->created_at->format('M d, Y H:i') }}
                    </small>
                    @if($note->updated_at->ne($note->created_at))
                        <small class="text-muted">
                            Last updated: {{ $note->updated_at->format('M d, Y H:i') }}
                        </small>
                    @endif
                </div>

                <div class="note-content py-3">
                    {!! nl2br(e($note->content)) !!}
                </div>
            </div>
        </div>

        @can('update', $note)
            <div class="card">
                <div class="card-header">
                    <h5>Update Note</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('notes.update', $note) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $note->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Content</label>
                            <textarea name="content" class="form-control" rows="5" required>{{ $note->content }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            @if($canDelete)
                                <button type="button" class="btn btn-danger"
                                    onclick="if(confirm('Delete this note?')) { document.getElementById('delete-form').submit(); }">
                                    Delete Note
                                </button>
                            @endif
                        </div>
                    </form>
                    @if($canDelete)
                        <form id="delete-form" action="{{ route('notes.destroy', $note) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        @endcan
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="content"]');

        if (textarea) {
            textarea.addEventListener('focus', function() {
                fetch(`/notes/{{ $note->id }}/start-editing`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                });
            });

            window.addEventListener('beforeunload', function() {
                fetch(`/notes/{{ $note->id }}/stop-editing`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    keepalive: true
                });
            });
        }
    });
    </script>
@endsection
