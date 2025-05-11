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

        // Buat array untuk frontend map (photo sudah jadi URL penuh)
        $destinationArray = $destinations->getCollection()->map(function ($dest) {
            return [
                'id' => $dest->id,
                'name' => $dest->name,
                'category' => $dest->category ? $dest->category->name : '-',
                'address' => $dest->address,
                'latitude' => $dest->latitude,
                'longitude' => $dest->longitude,
                'ticket_price' => $dest->ticket_price,
                'photo' => $dest->photo ? asset('storage/photos/' . $dest->photo) : null, // ✅ ini yang diperbaiki
            ];
        })->values()->all();

        $categories = Category::all();

        return view('explore.index', [
            'destinations' => $destinations,
            'categories' => $categories,
            'destinationArray' => $destinationArray,
            'orsApiKey' => env('ORS_API_KEY'),
        ]);
    }
}
