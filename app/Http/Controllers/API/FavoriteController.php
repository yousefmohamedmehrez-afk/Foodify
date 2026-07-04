<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;


class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::with([
            'meal.category',
            'meal.images'
        ])
        ->where('user_id', $request->user()->id)
        ->get();

        return response()->json($favorites);
    }

    public function store(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'meal_id' => $request->meal_id,
        ]);

        return response()->json([
            'message' => 'Added Successfully',
            'favorite' => $favorite
        ]);
    }

    public function destroy(Request $request, $mealId)
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('meal_id', $mealId)
            ->delete();

        return response()->json([
            'message' => 'Removed Successfully'
        ]);
    }
}