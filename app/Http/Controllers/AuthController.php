<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session; // Import the Session facade

class AuthController extends Controller
{
    public function showSignupForm()
    {
        return view('signup'); // Create this view in the next step
    }

    public function signup(Request $request)
    {
        // 1. Validate the form data
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255', // Username is required and must be unique
            'password' => 'required|min:6|confirmed', // Password is required, min 6 chars, and must match confirmation
        ]);

        if ($validator->fails()) {
            return redirect('/signup')
                        ->withErrors($validator)  // Send validation errors back to the form
                        ->withInput(); // Repopulate the form with the user's previous input
        }

        // 2. Create the user
        try {
            User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password), // Hash the password securely
            ]);

            return redirect('/login')->with('success', 'Signup successful! Please log in.'); // Redirect to login with a success message

        } catch (\Exception $e) {
            // 3. Handle database errors (e.g., if the unique constraint is violated even after validation)
            return redirect('/signup')->with('error', 'Signup failed. Please try again.'); // Or log the error for debugging
        }
    }

    public function showLoginForm() // Add this if you don't have a login controller yet.
    {
        return view('login');
    }

    // ... (signup methods remain the same)

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Successful login
            Session::put('user_id', $user->id);
            Session::put('username', $user->username);
            $request->session()->regenerate(); // Important: prevent session fixation

            return redirect('/dashboard');
        } else {
            // Authentication failed
            return back()->withErrors(['message' => 'Invalid credentials.'])->withInput($request->only('username'));
        }
    }

    public function dashboard()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $user = User::find(Session::get('user_id')); // Get the user from the database

        return view('dashboard', compact('user'));
    }

    public function logout(Request $request)
    {
        Session::forget('user_id'); // Remove user ID from session
        Session::forget('username'); // Remove username from session

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/login');
    }
}