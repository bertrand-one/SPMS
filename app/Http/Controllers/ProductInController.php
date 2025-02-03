<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductIn;
use App\Models\Product; // Import the Product model
use Illuminate\Support\Facades\Validator;

class ProductInController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Pcode' => 'required|exists:products,id', // Check if Pcode exists in products table
            'Inquantity' => 'required|integer|min:1',
            'Inprice' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            ProductIn::create($request->all()); // Create ProductIn record
            return back()->with('success', 'products are in successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding Product In. Please try again.');
        }
    }

    public function index()
    {
        $productIns = ProductIn::with('product') // Eager load the product relationship
            ->whereHas('product', function ($query) { // Filter by the product's user ID
                $query->where('user_id', session('user_id'));
            })
            ->get();

        $totalProductIns = $productIns->count();

        return view('stockin', compact('productIns', 'totalProductIns'));
    }

    // ... (create method remains the same)

    public function edit($id)
    {
        $productIn = ProductIn::findOrFail($id); 
        if ($productIn->product->user_id != session('user_id')) {
            abort(404); 
        }
        return response()->json($productIn); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Inquantity' => 'required|integer|min:1',
            'Inprice' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $productIn = ProductIn::findOrFail($id);

        if ($productIn->product->user_id != session('user_id')) {
            abort(404); 
        }

        try {
            $productIn->update($request->all());

            return response()->json(['success' => 'Product In updated successfully!']); 

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating Product In. Please try again.']); 
        }
    }

    public function destroy($id)
    {
        $productIn = ProductIn::findOrFail($id);

        if ($productIn->product->user_id != session('user_id')) {
            abort(404); 
        }

        $productIn->delete();

        return redirect()->back()->with('success', 'Product In deleted successfully!');
    }
    
}