<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Category;

class ExploreController extends Controller
{
    
public function index(Request $request)
{
    $query = Destination::with('category');

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->category) {
        $query->where('category_id', $request->category);
    }

    $destinations = $query->latest()->paginate(8);
    $categories = Category::all();

    return view('explore.index', compact('destinations', 'categories'));
}

}
