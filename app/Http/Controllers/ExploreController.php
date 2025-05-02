<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class ExploreController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('category')->get();
        return view('explore.index', compact('destinations'));
    }
}
