<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Category;

class DestinationController extends Controller
{
    // INDEX – Menampilkan semua destinasi
    public function index(Request $request)
    {
        $query = Destination::with('category');
    
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
    
        $destinations = $query->get();
        $categories = Category::all();
    
        return view('destinations.index', compact('destinations', 'categories'));
    }

    // CREATE – Form tambah destinasi
    public function create()
    {
    $categories = Category::all();
    return view('destinations.create', compact('categories'));
    }

    // STORE – Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ticket_price' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/photos', $filename);
            $validated['photo'] = $filename;
        }        

        Destination::create($validated);

        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    // EDIT – Tampilkan form edit
    public function edit(Destination $destination)
    {
    $categories = Category::all();
    return view('destinations.edit', compact('destination', 'categories'));
    }

    // UPDATE – Simpan perubahan
    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ticket_price' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

    // Jika ada upload foto baru
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/photos', $filename);
        $validated['photo'] = $filename;
    }

    $destination->update($validated); // ✅ Category_id sekarang ikut tersimpan

    return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil diupdate!');
    }

    // DESTROY – Hapus destinasi
    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil dihapus!');
    }
}
