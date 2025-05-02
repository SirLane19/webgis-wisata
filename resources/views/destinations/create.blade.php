@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Tambah Destinasi Wisata</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <strong>Oops!</strong> Ada kesalahan pada input kamu:
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('destinations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Nama Destinasi</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Alamat</label>
            <input type="text" name="address" value="{{ old('address') }}" required class="w-full border p-2 rounded">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude') }}" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-1">Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude') }}" required class="w-full border p-2 rounded">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Harga Tiket</label>
            <input type="text" name="ticket_price" value="{{ old('ticket_price') }}" class="w-full border p-2 rounded">
        </div>

        {{-- Dropdown kategori --}}
        <div>
            <label class="block font-semibold mb-1">Kategori</label>
            <select name="category_id" required class="...">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select> 
        </div>

        <div>
            <label class="block font-semibold mb-1">Foto Destinasi</label>
            <input type="file" name="photo" accept="image/*" class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('destinations.index') }}" class="text-gray-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
