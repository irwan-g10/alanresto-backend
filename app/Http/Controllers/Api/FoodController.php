<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
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
        // return response()->json([
        //     'success' => true,
        //     'data'    => $foods,
        // ]);
        return FoodResource::collection($foods)->additional([
            'success' => true,
            'message' => 'Daftar makanan berhasil diambil',
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
                'price' => 'required|integer|min:0',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ],
        );


        // Simpan file gambar
        $validated['image'] = $request->file('image')->store('/', 'public');

        $food = Food::create($validated);

        return (new FoodResource($food))->additional([
            'success' => true,
            'message' => 'Detail makanan berhasil diambil',
        ]);
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

        return (new FoodResource($food))->additional([
            'success' => true,
            'message' => 'Detail makanan berhasil diambil',
        ]);
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
