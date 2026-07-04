<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    // عرض جميع طرق الدفع
    public function index()
    {
        return response()->json([
    'payment_methods' => PaymentMethod::select('name')->get()
        ]);
    }

    // عرض طريقة دفع واحدة
    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return response()->json($paymentMethod);
    }
}