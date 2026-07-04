<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Meal;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // عرض السلة
   public function index(Request $request)
{
    $cart = Cart::with([
        'items.meal.category',
        'items.meal.images'
    ])->firstOrCreate([
        'user_id' => $request->user()->id
    ]);

    $total = $cart->items->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    return response()->json([
        'cart' => $cart,
        'total' => $total,
        'items_count' => $cart->items->count(),
    ]);
}
    // إضافة وجبة للسلة
    public function store(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        $meal = Meal::findOrFail($request->meal_id);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('meal_id', $meal->id)
            ->first();

        if ($item) {

            $item->increment('quantity', $request->quantity);

        } else {

            CartItem::create([
                'cart_id' => $cart->id,
                'meal_id' => $meal->id,
                'quantity' => $request->quantity,
                'price' => $meal->price,
            ]);
        }

        return response()->json([
            'message' => 'Meal Added To Cart Successfully'
        ]);
    }

    // تعديل الكمية
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::whereHas('cart', function ($q) use ($request) {
           $q->where('user_id', $request->user()->id);
        })->findOrFail($id);

        $item->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Cart Updated Successfully'
        ]);
    }

    // حذف عنصر من السلة
    public function destroy(Request $request, $id)    {
        $item = CartItem::whereHas('cart', function ($q) use ($request) {
      $q->where('user_id', $request->user()->id);
    })->findOrFail($id);

      $item->delete();
    }

    // تفريغ السلة
    public function clear(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'message' => 'Cart Cleared Successfully'
        ]);
    }
}