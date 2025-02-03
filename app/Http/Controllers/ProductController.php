<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Pcode' => 'required|unique:products|max:255',
            'Pname' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Product::create([
                'Pcode' => $request->Pcode,
                'Pname' => $request->Pname,
                'user_id' => Session::get('user_id'), // Get user ID from session
            ]);

            return back()->with('success', 'Product added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding product. Please try again.'); // Log the error in production
        }
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $products = Product::where('user_id', Session::get('user_id'))->get(); // Get products for the logged-in user

        // More efficient way to calculate stock and exhausted products:
        $productsWithStock = DB::table('products')
            ->select('products.id', 'products.Pname', 'products.Pcode', DB::raw('SUM(product_ins.Inquantity) - SUM(product_outs.Outquantity) as stock'))
            ->leftJoin('product_ins', 'products.id', '=', 'product_ins.Pcode')
            ->leftJoin('product_outs', 'products.id', '=', 'product_outs.Pcode')
            ->where('products.user_id', $userId)
            ->groupBy('products.id', 'products.Pname', 'products.Pcode')
            ->get();

        $totalProducts = $products->count(); // Count the total products

        return view('products', compact('products','productsWithStock','totalProducts')); // Create products.index view
    }


    public function edit($id)
    {
        $product = Product::find($id);
        if (!$product || $product->user_id != session('user_id')) {
            abort(404); // Or redirect with an error message
        }
        return response()->json($product); // Return product data as JSON
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Pcode' => 'required|unique:products,Pcode,' . $id . '|max:255', // Unique except for the current product
            'Pname' => 'required|max:255',
        ]);

        $product = Product::find($id);

        if (!$product || $product->user_id != session('user_id')) {
            abort(404); // Or redirect with an error message
        }

        try {
            $product->update([
                'Pcode' => $request->Pcode,
                'Pname' => $request->Pname,
            ]);

            return response()->json(['success' => 'Product updated successfully!']); // Return JSON success message

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating product. Please try again.']); // Return JSON error message
        }
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product || $product->user_id != session('user_id')) {
            abort(404); // Or redirect with an error message
        }
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}