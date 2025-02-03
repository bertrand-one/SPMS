<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPMS-Signup</title>

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
          <h1 class="logo">SPMS</h1>
          <h2 class="font-semibold">Create a new account.</h2>
          <form method="POST" action="{{ route('signup.submit') }}" class="mt-5 flex flex-col">
          @csrf  {{-- Important for security --}}
            Username: <input type="text" name="username" class="input" required><br>
            Password: <input type="password" name="password" class="input" required><br>
            Confirm password: <input type="password" name="password_confirmation" id="password_confirmation" class="input" required><br>
            <input type="submit" value="signup" class="btn-primary">

            @if ($errors->any())  {{-- Display validation errors --}}
           <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))  {{-- Display any other error messages --}}
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

          </form>
          <p class="mt-5">Have an account? <a href="/login" class="text-blue-800"><i class="bi bi-house"></i> Login here</a></p>
        </div>
    </body>
</html>


