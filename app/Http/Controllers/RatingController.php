<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;

class RatingController extends Controller
{

// Add a method to submit a rating for a product
public function submitRating(Request $request, $productId)
{
    // Validate the request data here

    $rating = new Rating();
    $rating->rating = $request->input('rating');
    $rating->review = $request->input('review');
    $rating->user_id = auth()->user()->id; // Assuming the user ID is stored in the user_id column
    $rating->product_id = $productId; // Set the product_id

    $rating->save();

    return response()->json(['message' => 'Rating submitted successfully'], 201);
}


// Add a method to fetch ratings for a product
public function getProductRatings($productId)
{
    $ratings = Rating::where('product_id', $productId)->with('user')->get();

    return response()->json($ratings);
}

public function getUserRatings(Request $request)
{
    $user = $request->user();
    $ratings = $user->ratings()->with('product')->get();

    return response()->json($ratings);
}


}
