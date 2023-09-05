<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;


class VendorController extends Controller
{
    public function addProduct(Request $request)
{
    $user = $request->user(); // Get the authenticated user (vendor)
    
    // Validate the product data
    $data = $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id', // Add this line
    ]);
    
    // Create a new product associated with the vendor
    $product = new Product($data);
    $product->vendor_id = $user->id;
    $product->save();
    
    return response()->json(['message' => 'Product added successfully']);
}

public function updateProduct(Request $request, $productId)
{
    $user = $request->user();

    // Find the product associated with the vendor
    $product = $user->products()->findOrFail($productId);

    // Validate and update the product data
    $data = $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id', // Make sure category_id is included
    ]);

    $product->update($data);

    return response()->json(['message' => 'Product updated successfully']);
}


    public function deleteProduct(Request $request, $productId)
    {
        $user = $request->user(); // Get the authenticated user (vendor)
        
        // Find the product associated with the vendor and delete it
        $product = $user->products()->findOrFail($productId);
        $product->delete();
        
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function updateOrder(Request $request, $orderId)
    {
        $user = $request->user(); // Get the authenticated user (vendor)
    
        // Find the order associated with the vendor's products
        $order = Order::whereHas('product', function ($query) use ($user) {
            $query->where('vendor_id', $user->id);
        })->findOrFail($orderId);
    
        // Validate and update the order data
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,shipped,delivered,sold', // Add more statuses if needed
        ]);
    
        $order->update($data);
    
        return response()->json(['message' => 'Order updated successfully']);
    }
    

    public function deleteOrder(Request $request, $orderId)
    {
        $user = $request->user(); // Get the authenticated user (vendor)

        // Find and delete the order associated with the vendor's products
        $order = Order::whereHas('product', function ($query) use ($user) {
            $query->where('vendor_id', $user->id);
        })->findOrFail($orderId);
        
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }


public function createOrder(Request $request)
{
    $user = $request->user(); // Get the authenticated user (customer)

    // Validate the order data
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'price' => 'required|numeric|min:0',
        'payment_method' => 'required|in:credit_card,paypal',
        'shipping_method' => 'required|in:standard,express',
        'shipping_address' => 'required|string',
    ]);

    // Create the order associated with the customer
    $orderNumber = 'ORD' . strtoupper(uniqid()); // Generate a unique order number
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

    return response()->json(['message' => 'Order created successfully', 'order_number' => $orderNumber]);
}
public function getVendorOrders()
{
    $orders = Order::all();

    return response()->json(['orders' => $orders]);
}

public function createCategory(Request $request)
{
    $user = $request->user(); // Get the authenticated user (vendor)

    $data = $request->validate([
        'name' => 'required|unique:categories'
    ]);

    $category = new Category($data);
    $category->vendor_id = $user->id; // Associate the category with the vendor
    $category->save();

    return response()->json(['message' => 'Category created successfully']);
}


}

