@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Edit Destinasi Wisata</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold">Nama</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $destination->name) }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Alamat</label>
            <input type="text" name="address" class="w-full border rounded p-2" value="{{ old('address', $destination->address) }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Latitude</label>
            <input type="text" name="latitude" class="w-full border rounded p-2" value="{{ old('latitude', $destination->latitude) }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Longitude</label>
            <input type="text" name="longitude" class="w-full border rounded p-2" value="{{ old('longitude', $destination->longitude) }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Harga Tiket</label>
            <input type="text" name="ticket_price" class="w-full border rounded p-2" value="{{ old('ticket_price', $destination->ticket_price) }}">
        </div>

        {{-- Dropdown Kategori --}}
        <div class="mb-4">
            <label class="block font-semibold">Kategori</label>
            <select name="category_id" required class="...">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $destination->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>            
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Foto Baru (opsional)</label>
            <input type="file" name="photo" accept="image/*" class="w-full border p-2 rounded">

            @if ($destination->photo)
                <p class="text-sm text-gray-600 mt-2">Foto saat ini:</p>
                <img src="{{ asset('storage/' . $destination->photo) }}" alt="Foto destinasi" class="w-32 h-auto mt-1 rounded shadow">
            @endif
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('destinations.index') }}" class="text-blue-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </div>
    </form>
</div>
@endsection
