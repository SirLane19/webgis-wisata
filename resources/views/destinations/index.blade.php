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

    {{-- Form Filter, Search, Sort, Reset --}}
    <form method="GET" action="{{ route('destinations.index') }}" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:flex-wrap gap-2 sm:gap-4">
        <input type="text" name="search" placeholder="Cari destinasi..." value="{{ request('search') }}"
            class="border p-2 rounded w-full sm:w-1/4" />

        <select name="category" class="border p-2 rounded w-full sm:w-1/4">
            <option value="">-- Semua Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="border p-2 rounded w-full sm:w-1/5">
            <option value="">-- Urutkan Berdasarkan --</option>
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
            <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Kategori</option>
        </select>

        <select name="order" class="border p-2 rounded w-full sm:w-1/5">
            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>A-Z</option>
            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Z-A</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('destinations.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
        </div>
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
                    <th class="border px-4 py-2">Foto</th>
                    <th class="border px-4 py-2">Hari Buka</th>
                    <th class="border px-4 py-2">Jam Buka</th>
                    <th class="border px-4 py-2">Jam Tutup</th>
                    <th class="border px-4 py-2 text-center">Status</th>
                    <th class="border px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 break-words">
            @forelse ($destinations as $dest)
                @php
                    $now = \Carbon\Carbon::now();
                    $dayName = strtolower($now->format('l'));
                    $isOpenToday = $dest->schedules->firstWhere('day', $dayName);
                    $isOpen = false;

                    if ($isOpenToday && $isOpenToday->open_time && $isOpenToday->close_time) {
                        try {
                            $openTime = \Carbon\Carbon::createFromTimeString($isOpenToday->open_time);
                            $closeTime = \Carbon\Carbon::createFromTimeString($isOpenToday->close_time);
                            $isOpen = $now->between($openTime, $closeTime);
                        } catch (Exception $e) {
                            $isOpen = false;
                        }
                    }
                @endphp
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $dest->name }}</td>
                    <td class="px-4 py-2">{{ $dest->address }}</td>
                    <td class="px-4 py-2">{{ $dest->category->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $dest->latitude }}</td>
                    <td class="px-4 py-2">{{ $dest->longitude }}</td>
                    <td class="px-4 py-2">
                        @if ($dest->photo)
                            <img src="{{ asset('storage/photos/' . $dest->photo) }}" alt="{{ $dest->name }}" class="h-16 object-cover mx-auto rounded" onerror="this.onerror=null;this.src='{{ asset('img/default.png') }}';">
                        @else
                            <span class="text-gray-400 italic">Tidak ada foto</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        {{ $isOpenToday && $isOpenToday->day ? ucfirst($isOpenToday->day) : '-' }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $isOpenToday && $isOpenToday->open_time ? \Carbon\Carbon::parse($isOpenToday->open_time)->format('H:i') : '-' }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $isOpenToday && $isOpenToday->close_time ? \Carbon\Carbon::parse($isOpenToday->close_time)->format('H:i') : '-' }}
                    </td>
                    <td class="px-4 py-2 font-semibold {{ $isOpen ? 'text-green-600' : 'text-red-500' }}">
                        {{ $isOpen ? 'Buka' : 'Tutup' }}
                    </td>
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
                    <td colspan="11" class="text-center text-gray-500 py-6">Belum ada destinasi yang ditambahkan.</td>
                </tr>
            @endforelse
</tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $destinations->links() }}
    </div>

    <div class="mt-6">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>

    <div class="mt-12">
        <h3 class="text-xl font-semibold mb-4">📍 Peta Lokasi Destinasi</h3>
        <div class="rounded overflow-hidden border border-gray-300 shadow w-full" style="height: 500px;" id="map"></div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<style>
    #map { height: 500px; }
</style>

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
