@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">🧭 Eksplorasi Destinasi Wisata</h1>

    {{-- Peta --}}
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3">Peta Lokasi</h2>
        <div id="map" class="w-full h-[400px] rounded shadow border"></div>
    </div>

    {{-- Form Pencarian & Filter --}}
    <form method="GET" action="{{ route('explore') }}" class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <input type="text" name="search" placeholder="Cari nama destinasi..." value="{{ request('search') }}"
            class="border p-2 rounded w-full sm:w-1/3" />

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

    {{-- Daftar Destinasi --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Hasil Destinasi</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($destinations as $dest)
                <div class="border rounded p-4 shadow hover:shadow-md transition bg-white">
                    <h3 class="text-lg font-bold">{{ $dest->name }}</h3>
                    <p class="text-blue-600 text-sm">{{ $dest->category->name ?? '-' }}</p>
                    <p class="text-gray-500 text-sm mb-2">{{ $dest->address }}</p>
                    <button onclick="showDetail({{ $dest->id }})" class="text-blue-600 hover:underline text-sm">Lihat Detail</button>
                </div>
            @empty
                <p class="text-center text-gray-500 col-span-3">Tidak ada destinasi ditemukan.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $destinations->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail Destinasi --}}
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow max-w-md w-full">
        <h2 class="text-xl font-bold mb-2" id="modalName"></h2>
        <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Kategori:</span> <span id="modalCategory"></span></p>
        <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Alamat:</span> <span id="modalAddress"></span></p>
        <p class="text-sm text-gray-700"><span class="font-semibold">Tiket:</span> <span id="modalTicket"></span></p>
        <div class="mt-4 text-right">
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-4 py-1 rounded hover:bg-gray-400">Tutup</button>
        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<style>
    #map { height: 400px; }
</style>

<script>
    const data = @json($destinations);

    function showDetail(id) {
        const dest = data.find(d => d.id === id);
        if (dest) {
            document.getElementById('modalName').innerText = dest.name;
            document.getElementById('modalCategory').innerText = dest.category?.name || '-';
            document.getElementById('modalAddress').innerText = dest.address;
            document.getElementById('modalTicket').innerText = dest.ticket_price || 'Gratis / Tidak disebutkan';
            document.getElementById('detailModal').classList.remove('hidden');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([-6.2, 106.8], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        data.forEach(dest => {
            if (dest.latitude && dest.longitude) {
                L.marker([dest.latitude, dest.longitude])
                    .addTo(map)
                    .bindPopup(`<strong>${dest.name}</strong><br>${dest.address}<br><em>${dest.category?.name || '-'}</em>`);
            }
        });
    });
</script>
@endsection
