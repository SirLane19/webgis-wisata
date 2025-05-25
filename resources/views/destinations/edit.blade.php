@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Destinasi Wisata</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div>
            <label class="block font-medium mb-1">Nama</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $destination->name) }}" required>
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block font-medium mb-1">Alamat</label>
            <input type="text" name="address" class="w-full border rounded p-2" value="{{ old('address', $destination->address) }}" required>
        </div>

        {{-- Lat-Long Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Latitude</label>
                <input type="text" name="latitude" class="w-full border rounded p-2" value="{{ old('latitude', $destination->latitude) }}" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Longitude</label>
                <input type="text" name="longitude" class="w-full border rounded p-2" value="{{ old('longitude', $destination->longitude) }}" required>
            </div>
        </div>

        {{-- Harga Tiket --}}
        <div>
            <label class="block font-medium mb-1">Harga Tiket</label>
            <input type="text" name="ticket_price" class="w-full border rounded p-2" value="{{ old('ticket_price', $destination->ticket_price) }}">
        </div>

        {{-- Dropdown Kategori --}}
        <div>
            <label class="block font-medium mb-1">Kategori</label>
            <select name="category_id" required class="w-full border rounded p-2">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $destination->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Upload Foto Baru --}}
        <div>
            <label class="block font-medium mb-1">Foto Baru (opsional)</label>
            <input type="file" name="photo" accept="image/*" class="w-full border p-2 rounded">
            @if ($destination->photo)
                <p class="text-sm text-gray-600 mt-2">Foto saat ini:</p>
                <img src="{{ asset('storage/photos/' . $destination->photo) }}" alt="Foto destinasi" class="w-32 rounded shadow mt-1">
            @endif
        </div>

        {{-- Jadwal Buka --}}
        <div>
            <label class="block font-medium mb-2">Jadwal Buka (Hari dan Jam)</label>
            <div id="schedule-container" class="flex flex-col gap-4">
                @foreach ($destination->schedules as $i => $s)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Hari</label>
                            <select name="schedules[{{ $i }}][day]" class="w-full border p-2 rounded" required>
                                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                    <option value="{{ $day }}" {{ $s->day === $day ? 'selected' : '' }}>
                                        {{ ucfirst($day) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Buka</label>
                            <input type="time" name="schedules[{{ $i }}][open_time]" value="{{ $s->open_time }}" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Tutup</label>
                            <input type="time" name="schedules[{{ $i }}][close_time]" value="{{ $s->close_time }}" class="w-full border p-2 rounded" required>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-between items-center pt-4">
            <a href="{{ route('destinations.index') }}" class="text-blue-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Update</button>
        </div>
    </form>
</div>
@endsection
