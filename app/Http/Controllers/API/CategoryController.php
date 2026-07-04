<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Get All Categories
    public function index()
    {
        return response()->json(Category::all());
    }

    // Create Category
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable||image|mimes:jpg,jpeg,png|max:2048',
    ]);

    //$imagePath = $request->file('image')->store('categories', 'public');

    $category = Category::create([
        'name' => $request->name,
        'image' => $request->image,
    ]);

    return response()->json([
        'message' => 'Category created successfully',
        'category' => $category
    ], 201);
}

    // Show Category
    public function show(Category $category)
    {
        return response()->json($category);
    }

    // Update Category
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());

        return response()->json([
            'message'=>'Category Updated',
            'category'=>$category
        ]);
    }

    // Delete Category
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message'=>'Category Deleted'
        ]);
    }
}