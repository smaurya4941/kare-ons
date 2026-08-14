<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        $user = $request->user();

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json(['message' => 'You have already submitted a review for this product.'], 409);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->comment,
            'status' => false,
        ]);

        $review->setRelation('product', $product);
        AdminNotification::notifyReviewPending($review);

        return response()->json([
            'message' => 'Thank you! Your review has been submitted and is pending moderation.',
        ], 201);
    }
}
