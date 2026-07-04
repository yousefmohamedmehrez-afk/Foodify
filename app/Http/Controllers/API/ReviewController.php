<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Meal;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // عرض جميع Reviews الخاصة بوجبة
    public function index($mealId)
    {
        $meal = Meal::with([
            'reviews.user'
        ])->findOrFail($mealId);

        return response()->json([
            'meal' => $meal->name,
            'average_rating' => round($meal->reviews()->avg('rating'), 1),
            'reviews_count' => $meal->reviews()->count(),
            'reviews' => $meal->reviews
        ]);
    }

    // إضافة Review
    public function store(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // منع المستخدم من تقييم نفس الوجبة أكثر من مرة
        $exists = Review::where('user_id', $request->user()->id)
            ->where('meal_id', $request->meal_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You have already reviewed this meal.'
            ], 400);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'meal_id' => $request->meal_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review added successfully.',
            'review' => $review->load('user')
        ], 201);
    }

    // تعديل Review
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review updated successfully.',
            'review' => $review
        ]);
    }

    // حذف Review
    public function destroy(Request $request, $id)
    {
        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.'
        ]);
    }
}