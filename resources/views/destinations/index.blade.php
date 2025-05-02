@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <h2 class="text-2xl font-bold">Daftar Destinasi</h2>
        <a href="{{ route('destinations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Filter dan Search --}}
    <form method="GET" action="{{ route('destinations.index') }}" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
        <input type="text" name="search" placeholder="Cari destinasi..." value="{{ request('search') }}" class="border p-2 rounded w-full sm:w-1/3" />

        <select name="category" class="border p-2 rounded w-full sm:w-1/4">
            <option value="">-- Semua Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan</button>
    </form>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto rounded shadow">
        <table class="min-w-full text-sm text-center border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Alamat</th>
                    <th class="border px-4 py-2">Kategori</th>
                    <th class="border px-4 py-2">Latitude</th>
                    <th class="border px-4 py-2">Longitude</th>
                    <th class="border px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 break-words">
                @forelse ($destinations as $dest)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $dest->name }}</td>
                        <td class="px-4 py-2">{{ $dest->address }}</td>
                        <td class="px-4 py-2">{{ $dest->category->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $dest->latitude }}</td>
                        <td class="px-4 py-2">{{ $dest->longitude }}</td>
                        <td class="px-4 py-2">
                            <div class="flex flex-col sm:flex-row justify-center gap-1 sm:gap-3">
                                <a href="{{ route('destinations.edit', $dest->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('destinations.destroy', $dest->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-6">Belum ada destinasi yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link Balik ke Dashboard --}}
    <div class="mt-6">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>

    {{-- PETA --}}
    <div class="mt-12">
        <h3 class="text-xl font-semibold mb-4">📍 Peta Lokasi Destinasi</h3>
        <div class="rounded overflow-hidden border border-gray-300 shadow w-full" style="height: 500px;" id="map"></div>
    </div>
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

{{-- Custom CSS for map --}}
<style>
    #map { height: 500px; }
</style>

{{-- Inisialisasi Peta --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([-6.2, 106.8], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        @foreach ($destinations as $dest)
            @if ($dest->latitude && $dest->longitude)
                L.marker([{{ $dest->latitude }}, {{ $dest->longitude }}])
                    .addTo(map)
                    .bindPopup(`<strong>{{ $dest->name }}</strong><br>{{ $dest->address }}<br><em>{{ $dest->category->name ?? '-' }}</em>`);
            @endif
        @endforeach
    });
</script>
@endsection
