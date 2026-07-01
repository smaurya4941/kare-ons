<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|boolean',
            'is_verified_purchase' => 'required|boolean',
            'admin_reply' => 'nullable|string',
        ]);

        $review->update($validated);

        return back()->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
