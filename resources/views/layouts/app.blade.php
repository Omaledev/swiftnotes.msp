<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>SwiftNotes - @yield('title')</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500&display=swap" rel="stylesheet"
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


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  </body>
</html>




