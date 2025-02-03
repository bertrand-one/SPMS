<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPMS-Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @endif
    </head>
    <body>
        <div class="flex items-center justify-center flex-col h-[100vh]">
        @if (session('success'))  {{-- Display the success message from signup --}}
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
          <h1 class="logo">SPMS</h1>
          <h2 class="font-semibold">Login to your account.</h2>
          <form  method="POST" action="{{ route('login.submit') }}" class="mt-5 flex flex-col">
          @csrf  {{-- ... your form fields ... --}}
            Username: <input type="text" name="username" class="input" required><br>
            Password: <input type="password" name="password" class="input" required><br>
            <input type="submit" value="Login" class="btn-primary">


            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

          </form>
          <p class="mt-5">Don't have an account? <a href="/signup" class="text-blue-800">Sign up here</a></p>
        </div>
    </body>
</html>


