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
    <form method="GET" action="{{ route('explore') }}" class="mb-6 grid gap-3 sm:grid-cols-3">
        <input type="text" name="search" placeholder="Cari nama destinasi..." value="{{ request('search') }}"
            class="border p-2 rounded w-full focus:ring focus:ring-blue-200" />

        <select name="category" class="border p-2 rounded w-full focus:ring focus:ring-blue-200">
            <option value="">-- Semua Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">🔎 Cari</button>
    </form>

    {{-- Daftar Destinasi --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Hasil Destinasi</h2>
        <p class="text-sm text-gray-600 mb-2">
            Menampilkan {{ $destinations->firstItem() }} - {{ $destinations->lastItem() }} dari {{ $destinations->total() }} destinasi
        </p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($destinations as $dest)
                <div class="border rounded p-4 shadow hover:shadow-md transition bg-white relative">
                    <div class="mb-2">
                        <h3 class="text-lg font-bold">{{ $dest->name }}</h3>
                        <p class="text-blue-600 text-sm">{{ $dest->category->name ?? '-' }}</p>
                        <p class="text-gray-500 text-sm">{{ $dest->address }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            type="button"
                            class="text-sm text-blue-600 hover:underline lihat-detail"
                            data-name="{{ $dest->name }}"
                            data-category="{{ $dest->category->name ?? '-' }}"
                            data-address="{{ $dest->address }}"
                            data-ticket="{{ $dest->ticket_price ?? 'Gratis / Tidak disebutkan' }}"
                            data-latitude="{{ $dest->latitude }}"
                            data-longitude="{{ $dest->longitude }}">
                            🔍 Detail
                        </button>

                        <button class="favorite-btn" data-id="{{ $dest->id }}">
                            <span class="text-xl transition" id="fav-icon-{{ $dest->id }}">🤍</span>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 col-span-3">
                    Tidak ada destinasi ditemukan.
                    <a href="{{ route('explore') }}" class="text-blue-500 hover:underline">Reset filter?</a>
                </p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $destinations->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail Destinasi --}}
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[1000]">
    <div class="flex items-center justify-center min-h-screen w-full">
        <div class="bg-white z-[1001] p-6 rounded shadow max-w-md w-full relative transition transform duration-300 scale-95">
            <button onclick="tutupModal()" class="absolute top-3 right-4 text-gray-500 hover:text-black text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-2" id="modalName"></h2>
            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Kategori:</span> <span id="modalCategory"></span></p>
            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Alamat:</span> <span id="modalAddress"></span></p>
            <p class="text-sm text-gray-700"><span class="font-semibold">Tiket:</span> <span id="modalTicket"></span></p>
        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>

<script>
    let map, userLat, userLng, routingControl;

    function bukaModal() {
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function toggleFavorite(id) {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        if (favorites.includes(id)) {
            favorites = favorites.filter(fav => fav !== id);
        } else {
            favorites.push(id);
        }
        localStorage.setItem('favorites', JSON.stringify(favorites));
        updateFavoriteIcons();
    }

    function updateFavoriteIcons() {
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            const id = parseInt(btn.getAttribute('data-id'));
            const icon = document.getElementById(`fav-icon-${id}`);
            if (favorites.includes(id)) {
                icon.textContent = '❤️';
                icon.classList.add('text-red-500');
            } else {
                icon.textContent = '🤍';
                icon.classList.remove('text-red-500');
            }
        });
    }

    function showRouteToDestination(destLat, destLng) {
        if (!userLat || !userLng || !destLat || !destLng) return;
        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(userLat, userLng),
                L.latLng(destLat, destLng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: { styles: [{ color: 'blue', weight: 4 }] }
        }).addTo(map);

        map.panTo([destLat, destLng]);
    }

    document.addEventListener("DOMContentLoaded", function () {
        map = L.map('map').setView([-6.2, 106.8], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    L.marker([userLat, userLng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
                    map.setView([userLat, userLng], 13);
                });
            }

        const data = @json($destinationArray);
        data.forEach(dest => {
            if (dest.latitude && dest.longitude) {
                L.marker([dest.latitude, dest.longitude])
                    .addTo(map)
                    .bindPopup(`<strong>${dest.name}</strong><br>${dest.address}<br><em>${dest.category?.name || '-'}</em>`);
            }
        });

        document.querySelectorAll('.lihat-detail').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalName').innerText = this.dataset.name;
                document.getElementById('modalCategory').innerText = this.dataset.category;
                document.getElementById('modalAddress').innerText = this.dataset.address;
                document.getElementById('modalTicket').innerText = this.dataset.ticket;
                const lat = parseFloat(this.dataset.latitude);
                const lng = parseFloat(this.dataset.longitude);
                if (lat && lng) showRouteToDestination(lat, lng);
                bukaModal();
            });
        });

        document.getElementById('detailModal').addEventListener('click', function (e) {
            if (e.target === this) tutupModal();
        });

        updateFavoriteIcons();

        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const id = parseInt(this.getAttribute('data-id'));
                toggleFavorite(id);
            });
        });
    });
</script>
@endsection
