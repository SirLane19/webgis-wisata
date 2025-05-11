@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">📈 Statistik Eksplorasi</h1>

    <div class="grid gap-6">
        {{-- Statistik Kategori --}}
        <div class="bg-white border rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">📊 Jumlah Destinasi per Kategori</h2>
            <canvas id="chartKategori"></canvas>
        </div>

        {{-- Kartu Favorit --}}
        <div class="bg-white border rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">🔥 Top 5 Destinasi Paling Disukai</h2>
            <div id="cardFavorit" class="grid sm:grid-cols-2 gap-4"></div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('explore') }}" class="text-blue-600 hover:underline">← Kembali ke Eksplorasi</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const data = @json($destinationArray);

        // Chart Kategori
        const kategoriCounts = {};
        data.forEach(dest => {
            const cat = dest.category || 'Lainnya';
            kategoriCounts[cat] = (kategoriCounts[cat] || 0) + 1;
        });

        const ctx1 = document.getElementById('chartKategori').getContext('2d');
        if (window.Chart) {
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: Object.keys(kategoriCounts),
                    datasets: [{
                        label: 'Jumlah per Kategori',
                        data: Object.values(kategoriCounts),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Kartu Favorit
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const favCounts = {};
        favorites.forEach(id => {
            favCounts[id] = (favCounts[id] || 0) + 1;
        });

        const ranked = data.filter(d => favCounts[d.id])
                           .sort((a, b) => favCounts[b.id] - favCounts[a.id])
                           .slice(0, 5);

        const cardContainer = document.getElementById('cardFavorit');
        if (ranked.length === 0) {
            cardContainer.innerHTML = '<p class="text-gray-500 italic">Belum ada destinasi favorit</p>';
        } else {
            ranked.forEach(dest => {
                const div = document.createElement('div');
                div.className = 'border rounded-lg shadow-md bg-white overflow-hidden';
                div.innerHTML = `
                    <img src="${dest.photo || '/img/default.png'}" alt="${dest.name}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">${dest.name}</h3>
                        <p class="text-sm text-gray-600">Kategori: ${dest.category}</p>
                        <p class="text-sm text-gray-600">Alamat: ${dest.address}</p>
                        <p class="text-sm text-gray-600">Tiket: ${dest.ticket_price ?? 'Gratis / Tidak disebutkan'}</p>
                    </div>
                `;
                cardContainer.appendChild(div);
            });
        }
    });
</script>
@endsection
