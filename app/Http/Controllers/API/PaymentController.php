<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(
            Payment::with('order')->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Payment::with('order')->findOrFail($id)
        );
    }
}