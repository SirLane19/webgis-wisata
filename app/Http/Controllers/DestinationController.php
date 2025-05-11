<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Destination;
use App\Models\Category;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with('category')
            ->leftJoin('categories', 'destinations.category_id', '=', 'categories.id')
            ->select('destinations.*');

        if ($request->search) {
            $query->where('destinations.name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('destinations.category_id', $request->category);
        }

        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');

        if ($sort === 'category') {
            $query->orderBy('categories.name', $order);
        } else {
            $query->orderBy("destinations.$sort", $order);
        }

        $destinations = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('destinations.index', compact('destinations', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // dd("user");
            $filename = time() . '_' . Str::slug($request->name) . '.' . $request->photo->extension();
            $request->photo->storeAs('public/photos', $filename); // ← ini baris yang kamu cari
            $validated['photo'] = $filename;
        }

        Destination::create($validated);

        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::all();
        return view('destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if ($destination->photo && Storage::disk('public')->exists('photos/' . $destination->photo)) {
            Storage::disk('public')->delete('photos/' . $destination->photo);
        }

        $filename = time() . '_' . Str::slug($request->name) . '.' . $request->photo->extension();
        $request->photo->storeAs('photos', $filename, 'public'); // ✅ simpan ke storage/app/public/photos
        $validated['photo'] = $filename;
    }

    $destination->update($validated);

    return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil diperbarui.');
}

    public function destroy(Destination $destination)
    {
        if ($destination->photo && Storage::exists('public/photos/' . $destination->photo)) {
            Storage::delete('public/photos/' . $destination->photo);
        }

        $destination->delete();
        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil dihapus!');
    }
}


