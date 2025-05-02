@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">🧭 Eksplorasi Destinasi Wisata</h1>

    {{-- Peta --}}
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3">Peta Lokasi</h2>
        <div id="map" class="w-full h-[500px] rounded shadow border"></div>
    </div>

    {{-- Daftar Destinasi --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Daftar Destinasi</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($destinations as $dest)
                <div class="border rounded p-4 shadow hover:shadow-md transition">
                    <h3 class="text-lg font-bold">{{ $dest->name }}</h3>
                    <p class="text-gray-600">{{ $dest->category->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $dest->address }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<style>
    #map { height: 500px; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([-6.2, 106.8], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        @foreach ($destinations as $dest)
            @if ($dest->latitude && $dest->longitude)
                L.marker([{{ $dest->latitude }}, {{ $dest->longitude }}])
                    .addTo(map)
                    .bindPopup(`<strong>{{ $dest->name }}</strong><br>{{ $dest->address }}`);
            @endif
        @endforeach
    });
</script>
@endsection
