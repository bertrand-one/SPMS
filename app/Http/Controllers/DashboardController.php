<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // Import Session

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id'); // Get user ID from session

        $totalProducts = Product::where('user_id', $userId)->count(); // Filter products by user

        $totalStockIn = ProductIn::whereHas('product', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('Inquantity');

        $totalStockOut = ProductOut::whereHas('product', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->sum('Outquantity');


        $productsWithStock = DB::table('products')
            ->select('products.id', 'products.Pname', 'products.Pcode', DB::raw('SUM(product_ins.Inquantity) - SUM(product_outs.Outquantity) as stock'))
            ->leftJoin('product_ins', 'products.id', '=', 'product_ins.Pcode')
            ->leftJoin('product_outs', 'products.id', '=', 'product_outs.Pcode')
            ->where('products.user_id', $userId) // Filter by user ID
            ->groupBy('products.id', 'products.Pname', 'products.Pcode')
            ->get();

        $exhaustedProductsCount = $productsWithStock->filter(function ($product) {
            return $product->stock <= 0;
        })->count();

        $recentTransactions = collect();

        $recentIns = ProductIn::with('product')
            ->whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                $transaction->type = 'IN';
                return $transaction;
            });

        $recentOuts = ProductOut::with('product')
            ->whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                $transaction->type = 'OUT';
                return $transaction;
            });

        $recentTransactions = $recentIns->merge($recentOuts)->sortByDesc('created_at')->take(10);

        return view('dashboard', compact('totalProducts', 'totalStockIn', 'totalStockOut', 'productsWithStock', 'exhaustedProductsCount', 'recentTransactions'));
    }
}