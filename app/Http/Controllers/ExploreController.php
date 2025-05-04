<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Category;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data destinasi dengan kategori (eager loading)
        $destinations = Destination::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        // Konversi koleksi paginate ke array biasa untuk JavaScript
        $destinationArray = $destinations->getCollection()->values()->all();

        $categories = Category::all();

            return view('explore.index', [
                'destinations' => $destinations,
                'categories' => $categories,
                'destinationArray' => $destinations->getCollection()->values()->all(), // ✅ Tambahkan koma
                'orsApiKey' => env('ORS_API_KEY'), 
            ]);
    }
}
