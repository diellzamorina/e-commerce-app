<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class CustomerController extends Controller
{

    public function getProductDetails($productId)
{
    $product = Product::findOrFail($productId);
    return response()->json($product);
}

public function getProductCategories()
{
    $categories = Category::with('subcategories')->whereNull('parent_id')->get();
    return response()->json($categories);
}


public function makePurchase(Request $request)
{
    $user = $request->user();

    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'price' => 'required|numeric|min:0',
        'payment_method' => 'required|in:credit_card,paypal',
        'shipping_method' => 'required|in:standard,express',
        'shipping_address' => 'required|string',
    ]);


    $orderNumber = 'ORD' . strtoupper(uniqid()); 
    $order = new Order([
        'order_number' => $orderNumber,
        'customer_id' => $user->id,
        'vendor_id' => Product::findOrFail($data['product_id'])->vendor_id,
        'product_id' => $data['product_id'],
        'price' => $data['price'],
        'status' => 'pending',
        'payment_method' => $data['payment_method'],
        'shipping_method' => $data['shipping_method'],
        'shipping_address' => $data['shipping_address'],
    ]);
    $order->save();

    return response()->json(['message' => 'Purchase successful', 'order_number' => $orderNumber]);
}
public function searchProducts(Request $request)
{
    $keyword = $request->query('keyword');
    
    $products = Product::where('name', 'like', "%$keyword%")
        ->orWhere('description', 'like', "%$keyword%")
        ->get();
    
    return response()->json($products);
}

public function filterProductsByCategory(Request $request)
{
    $categoryId = $request->query('category_id');

    // Check if the category ID is provided
    if (!$categoryId) {
        return response()->json(['error' => 'Category ID is required.'], 400);
    }

    // Retrieve products that belong to the specified category
    $products = Product::where('category_id', $categoryId)->paginate(10);

    return response()->json($products);
}

public function sortProductsByPrice(Request $request)
{
    $sortOrder = $request->query('sort_order', 'asc');

    // Sort products by price based on the specified order
    $products = Product::orderBy('price', $sortOrder)->paginate(10);

    return response()->json($products);
}



}

