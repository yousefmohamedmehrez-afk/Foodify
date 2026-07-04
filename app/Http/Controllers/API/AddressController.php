<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // عرض كل العناوين
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->addresses
        );
    }

    // إضافة عنوان
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'building' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $address = Address::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'city' => $request->city,
            'street' => $request->street,
            'building' => $request->building,
            'floor' => $request->floor,
            'apartment' => $request->apartment,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Address Added Successfully',
            'address' => $address
        ], 201);
    }

    // عرض عنوان واحد
    public function show(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($address);
    }

    // تعديل عنوان
    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $address->update($request->only([
            'title',
            'city',
            'street',
            'building',
            'floor',
            'apartment',
            'notes',
        ]));

        return response()->json([
            'message' => 'Address Updated Successfully',
            'address' => $address
        ]);
    }

    // حذف عنوان
    public function destroy(Request $request, $id)
    {
        $address = Address::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $address->delete();

        return response()->json([
            'message' => 'Address Deleted Successfully'
        ]);
    }
}