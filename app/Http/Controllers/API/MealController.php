<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\MealImage;
use Illuminate\Http\Request;

class MealController extends Controller
{
    // عرض جميع الوجبات
    public function index()
    {
        $meals = Meal::with(['category', 'images'])->get();

        return response()->json($meals);
    }
    // search
    public function search(Request $request)
{
    $request->validate([
        'name' => 'required|string'
    ]);

    $meals = Meal::with(['category', 'images'])
        ->where('name', 'like', '%' . $request->name . '%')
        ->get();

    return response()->json($meals);
}
//Filter by Category
public function categoryMeals($id)
{
    $meals = Meal::with(['category', 'images'])
        ->where('category_id', $id)
        ->get();

    return response()->json($meals);
}

    // إضافة وجبة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
           // 'images' => 'nullable|array|min:1',
            //'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $meal = Meal::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_available' => true,
        ]);

       // foreach ($request->file('images') as $image) {

           // $path = $image->store('meals', 'public');

          //  MealImage::create([
             //   'meal_id' => $meal->id,
            //    'image' => $path,
          //  ]);
       // }

        return response()->json([
            'message' => 'Meal Created Successfully',
            'meal' => $meal->load(['category', 'images'])
        ], 201);
    }

    // عرض وجبة واحدة
    public function show(Meal $meal)
    {
        return response()->json(
            $meal->load(['category', 'images'])
        );
    }

    // تعديل وجبة
    public function update(Request $request, Meal $meal)
    {
        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'is_available' => 'sometimes|boolean',
        ]);

        $meal->update($request->only([
            'category_id',
            'name',
            'description',
            'price',
            'is_available'
        ]));

        if ($request->hasFile('images')) {

            foreach ($meal->images as $image) {

                if (file_exists(storage_path('app/public/' . $image->image))) {
                    unlink(storage_path('app/public/' . $image->image));
                }

                $image->delete();
            }

            foreach ($request->file('images') as $file) {

                $path = $file->store('meals', 'public');

                MealImage::create([
                    'meal_id' => $meal->id,
                    'image' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Meal Updated Successfully',
            'meal' => $meal->load(['category', 'images'])
        ]);
    }

    // حذف وجبة
    public function destroy(Meal $meal)
    {
        foreach ($meal->images as $image) {

            if (file_exists(storage_path('app/public/' . $image->image))) {
                unlink(storage_path('app/public/' . $image->image));
            }
        }

        $meal->delete();

        return response()->json([
            'message' => 'Meal Deleted Successfully'
        ]);
    }
}