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
        // Gunakan Eloquent murni agar relasi schedules bisa terbaca
        $query = Destination::with(['category', 'schedules']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Sorting aman tanpa leftJoin
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');

        if (in_array($sort, ['name', 'latitude', 'longitude'])) {
            $query->orderBy($sort, $order);
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
            'ticket_price' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedules' => 'array',
            'schedules.*.day' => 'nullable|string',
            'schedules.*.open_time' => 'nullable',
            'schedules.*.close_time' => 'nullable',
        ]);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . Str::slug($request->name) . '.' . $request->photo->extension();
            $request->photo->storeAs('public/photos', $filename);
            $validated['photo'] = $filename;
        }

        $destination = Destination::create($validated);

        if ($request->has('schedules')) {
            foreach ($request->schedules as $schedule) {
                if (!empty($schedule['day']) && !empty($schedule['open_time']) && !empty($schedule['close_time'])) {
                    $destination->schedules()->create([
                        'day' => $schedule['day'],
                        'open_time' => $schedule['open_time'],
                        'close_time' => $schedule['close_time'],
                    ]);
                }
            }
        }

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
            'ticket_price' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedules' => 'array',
            'schedules.*.day' => 'nullable|string',
            'schedules.*.open_time' => 'nullable',
            'schedules.*.close_time' => 'nullable',
        ]);

        // Handle foto baru
        if ($request->hasFile('photo')) {
            if ($destination->photo && Storage::disk('public')->exists('photos/' . $destination->photo)) {
                Storage::disk('public')->delete('photos/' . $destination->photo);
            }

            $filename = time() . '_' . Str::slug($request->name) . '.' . $request->photo->extension();
            $request->photo->storeAs('public/photos', $filename);
            $validated['photo'] = $filename;
        }

        // Update data destinasi utama
        $destination->update($validated);

        // Hapus semua jadwal lama dan buat ulang
        $destination->schedules()->delete();

        if ($request->has('schedules')) {
            foreach ($request->schedules as $schedule) {
                if (!empty($schedule['day']) && !empty($schedule['open_time']) && !empty($schedule['close_time'])) {
                    $destination->schedules()->create([
                        'day' => $schedule['day'],
                        'open_time' => $schedule['open_time'],
                        'close_time' => $schedule['close_time'],
                    ]);
                }
            }
        }

        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil diperbarui.');
    }


    public function destroy(Destination $destination)
    {
        if ($destination->photo && Storage::disk('public')->exists('photos/' . $destination->photo)) {
            Storage::disk('public')->delete('photos/' . $destination->photo);
        }

        $destination->schedules()->delete();
        $destination->delete();

        return redirect()->route('destinations.index')->with('success', 'Destinasi berhasil dihapus!');
    }
}
