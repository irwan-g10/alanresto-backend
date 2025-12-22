<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data food dari database
        $foods = Food::all();

        // Return JSON response dengan struktur konsisten
        return response()->json([
            'success' => true,
            'data'    => $foods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate(
            [
                'name'  => 'required|string|max:255',
                'price' => 'required|integer|min:0', // pakai integer saja
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:10240',
            ],
        );


        // Simpan file gambar
        $validated['image'] = $request->file('image')->store('foods', 'public');

        $food = Food::create($validated);

        return response()->json([
            'success' => true,
            'data' => $food
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari data food berdasarkan id
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }

        // Return data dengan status 200 OK
        return response()->json([
            'success' => true,
            'data' => $food
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found'
            ], 404);
        }

        // Hapus data
        $food->delete();

        return response()->noContent();
    }
}
