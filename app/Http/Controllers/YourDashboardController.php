<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YourDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard'); // Create this view (resources/views/dashboard.blade.php)
    }
}