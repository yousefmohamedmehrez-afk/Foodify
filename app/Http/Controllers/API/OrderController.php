<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;


class OrderController extends Controller
{
    // إنشاء طلب جديد
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $cart = Cart::with('items.meal')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {

            $total = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            // إنشاء الطلب
            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => $request->address_id,
                'payment_method_id' => $request->payment_method_id,
                'total' => $total,
                'status' => 'pending',
            ]);

            // إنشاء عناصر الطلب
            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'meal_id' => $item->meal_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // إنشاء عملية الدفع
            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'status' => 'pending',
                'transaction_id' => null,
                'paid_at' => null,
            ]);

            // حذف عناصر السلة
            $cart->items()->delete();

            Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Order Created',
            'message' => 'Your order #' . $order->id . ' has been created successfully.',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order Created Successfully',
                'order' => $order->load([
                    'items.meal.images',
                    'payment',
                    'paymentMethod',
                    'address',
                ])
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
public function track(Request $request, $id)
{
    $order = Order::with([
        'items.meal.images',
        'address',
        'paymentMethod',
        'payment'
    ])
    ->where('id', $id)
    ->where('user_id', $request->user()->id)
    ->firstOrFail();

    return response()->json([
        'order_id' => $order->id,
        'status' => $order->status,
        'total' => $order->total,
        'created_at' => $order->created_at,
        'address' => $order->address,
        'payment_method' => $order->paymentMethod,
        'payment' => $order->payment,
        'items' => $order->items,
    ]);
}
  public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,confirmed,preparing,on_the_way,delivered,cancelled'
    ]);

    $order = Order::findOrFail($id);

    $order->update([
        'status' => $request->status
    ]);

    Notification::create([
        'user_id' => $order->user_id,
        'title' => 'Order Updated',
        'message' => 'Your order status is now: ' . $request->status,
    ]);

    return response()->json([
        'message' => 'Order status updated successfully.',
        'order' => $order
    ]);
}
    // عرض جميع طلبات المستخدم
    public function index(Request $request)
    {
        $orders = Order::with([
            'items.meal.images',
            'payment',
            'paymentMethod',
            'address',
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

        return response()->json($orders);
    }

    // عرض طلب واحد
    public function show(Request $request, $id)
    {
        $order = Order::with([
            'items.meal.images',
            'payment',
            'paymentMethod',
            'address',
        ])
        ->where('user_id', $request->user()->id)
        ->findOrFail($id);

        return response()->json($order);
    }
}