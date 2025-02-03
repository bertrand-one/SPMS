<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductOut;
use App\Models\Product; // Import the Product model
use Illuminate\Support\Facades\Validator;

class ProductOutController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Pcode' => 'required|exists:products,id', // Check if Pcode exists in products table
            'Outquantity' => 'required|integer|min:1',
            'Outprice' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            ProductOut::create($request->all()); // Create ProductIn record
            return back()->with('success', 'products are out successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing Products. Please try again.');
        }
    }

    public function index()
    {
        $productOuts = ProductOut::with('product') // Eager load the product relationship
            ->whereHas('product', function ($query) { // Filter by the product's user ID
                $query->where('user_id', session('user_id'));
            })
            ->get();

        $totalProductOuts = $productOuts->count();

        return view('stockout', compact('productOuts', 'totalProductOuts'));
    }

    // ... (create method remains the same - if you have one)

    public function edit($id)
    {
        $productOut = ProductOut::findOrFail($id); 
        if ($productOut->product->user_id != session('user_id')) {
            abort(404); 
        }
        return response()->json($productOut); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Outquantity' => 'required|integer|min:1',
            'Outprice' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $productOut = ProductOut::findOrFail($id);

        if ($productOut->product->user_id != session('user_id')) {
            abort(404); 
        }

        try {
            $productOut->update($request->all());

            return response()->json(['success' => 'Product Out updated successfully!']); 

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating Product Out. Please try again.']); 
        }
    }

    public function destroy($id)
    {
        $productOut = ProductOut::findOrFail($id);

        if ($productOut->product->user_id != session('user_id')) {
            abort(404); 
        }

        $productOut->delete();

        return redirect()->back()->with('success', 'Product Out deleted successfully!');
    }


}