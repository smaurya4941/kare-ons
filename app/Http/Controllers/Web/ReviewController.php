<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        // Prevent duplicate reviews — one review per user per product
        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You have already submitted a review for this product.'], 403);
            }
            return back()->with('error', 'You have already submitted a review for this product.');
        }

        try {
            // The DB column is named `review`, not `comment`
            Review::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'rating'     => $request->rating,
                'title'      => $request->title,
                'review'     => $request->comment,
                'status'     => false, // default pending moderation
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Thank you! Your review has been submitted and is pending moderation.'], 200);
            }
            return back()->with('success', 'Thank you! Your review has been submitted and is pending moderation.');
        } catch (\Exception $e) {
            report($e);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Failed to submit review due to an unexpected error.'], 500);
            }
            return back()->withInput()->with('error', 'Failed to submit review due to an unexpected error.');
        }
    }
}
