<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>SwiftNotes - @yield('title')</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      @stack('styles')
  </head>
  <body class="bg-gray-100 min-h-screen flex flex-col">
        <header class="shadow-md mx-1 sticky top-0 z-50">
            @include('layouts.header')
        </header>

        <main class="flex-grow container mx-auto px-4 py-8">
            @yield('content')
        </main>

        <footer class="bg-gray-800 text-white py-4 mt-auto mx-1">
            @include('layouts.footer')
        </footer>

        @auth
            <script>
                // window variables with null checks
                window.currentTeamId = {{ $team->id ?? 'null' }};
                window.currentNoteId = {{ $note->id ?? 'null' }};
                window.user = @json(auth()->user() ?? null);
                window.PUSHER_APP_KEY = "{{ config('broadcasting.connections.pusher.key') }}";
                window.PUSHER_APP_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";

                  // for debugging
        console.log('Current team ID:', window.currentTeamId);
            </script>
        @endauth


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  </body>
</html>




