<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPMS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @endif
    </head>
    <body>
        <div class="flex items-center justify-center flex-col h-[100vh] w-full gap-5">
            <h1 class="logo">SPMS</h1>
          <h2 class="text-4xl font-semibold">welcome</h2>
          <p>This is a school products management system.</p>
          <button class="btn-primary"><a href="/login">START USING</a></button>
        </div>
    </body>
</html>
