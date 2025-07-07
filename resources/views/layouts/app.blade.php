<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>SwiftNotes - @yield('title')</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      @stack('styles')
  </head>
  <body class="bg-gray-100 min-h-screen flex flex-col">
        <header>
            @include('layouts.header')
        </header>

        <main class="flex-grow container mx-auto px-4 py-8">
            @yield('content')
        </main>

        <footer>
            @include('layouts.footer')
        </footer>

        @stack('scripts')
  </body>
</html>
