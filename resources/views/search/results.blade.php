@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Search Results for "{{ $query }}"</h1>

        @if($notes->isEmpty() && $teams->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">No results found for your search.</p>
            </div>
        @else
            <!-- Notes Results -->
            @if($notes->isNotEmpty())
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Notes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($notes as $note)
                            <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-medium text-lg mb-1">
                                    <a href="{{ route('notes.show', $note) }}" class="text-blue-600 hover:underline">
                                        {{ $note->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    In team: {{ $note->team->name }}
                                </p>
                                <p class="text-gray-800 line-clamp-2">
                                    {{ Str::limit(strip_tags($note->content), 150) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Teams Results -->
            @if($teams->isNotEmpty())
                <div>
                    <h2 class="text-xl font-semibold mb-4">Teams</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($teams as $team)
                            <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-medium text-lg mb-1">
                                    <a href="{{ route('notes.index', $team) }}" class="text-blue-600 hover:underline">
                                        {{ $team->name }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $team->members->count() }} members
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle mobile search
            const searchToggle = document.querySelector('[data-collapse-toggle="navbar-search"]');
            const mobileSearch = document.getElementById('navbar-search');

            if (searchToggle && mobileSearch) {
                searchToggle.addEventListener('click', function() {
                    mobileSearch.classList.toggle('hidden');
                });
            }

            // Auto-focus on mobile search when opened
            const mobileSearchInput = document.getElementById('search-navbar-mobile');
            if (mobileSearchInput) {
                searchToggle?.addEventListener('click', function() {
                    if (!mobileSearch.classList.contains('hidden')) {
                        mobileSearchInput.focus();
                    }
                });
            }

            // Submit search on enter (desktop)
            const desktopSearch = document.getElementById('search-navbar');
            if (desktopSearch) {
                desktopSearch.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        const query = encodeURIComponent(this.value);
                        window.location.href = `/search?query=${query}`;
                    }
                });
            }
        });

    </script>
@endsection
