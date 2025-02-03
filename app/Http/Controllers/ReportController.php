<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductIn;
use App\Models\ProductOut;
use App\Models\Product; // Import the Product model
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ... (index method remains the same)

    public function generate(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:IN,OUT',
            'first_date' => 'required|date',
            'second_date' => 'required|date|after_or_equal:first_date',
        ]);

        $transactionType = $request->transaction_type;
        $firstDate = $request->first_date;
        $secondDate = $request->second_date;

        if ($transactionType == 'IN') {
            $transactions = ProductIn::with('product')
                ->whereHas('product', function ($query) {
                    $query->where('user_id', session('user_id'));
                })
                ->whereBetween('date', [$firstDate, $secondDate])
                ->get();
        } else { // OUT
            $transactions = ProductOut::with('product')
                ->whereHas('product', function ($query) {
                    $query->where('user_id', session('user_id'));
                })
                ->whereBetween('date', [$firstDate, $secondDate])
                ->get();
        }

        $totalTransactions = $transactions->count();

        return view('reports', compact('transactions', 'totalTransactions', 'firstDate', 'secondDate', 'transactionType'));
    }

    public function index()
    {
        return view('reports'); 
    }
}