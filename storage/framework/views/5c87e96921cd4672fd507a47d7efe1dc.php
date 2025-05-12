

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto mt-10 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">📈 Statistik Eksplorasi</h1>

    <div class="grid gap-6">
        
        <div class="bg-white border rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">📊 Jumlah Destinasi per Kategori</h2>
            <canvas id="chartKategori"></canvas>
        </div>

        
        <div class="bg-white border rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">🔥 Top 5 Destinasi Paling Disukai</h2>
            <div id="cardFavorit" class="grid sm:grid-cols-2 gap-4"></div>
        </div>

        
        <div class="bg-white border rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">📍 3 Destinasi Terdekat dari Lokasi Kamu</h2>
            <div id="nearestPlaces" class="grid sm:grid-cols-2 gap-4 text-sm"></div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('explore')); ?>" class="inline-block bg-gradient-to-r from-blue-500 to-blue-700 text-white px-4 py-2 rounded shadow hover:brightness-110">← Kembali ke Eksplorasi</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const data = <?php echo json_encode($destinationArray, 15, 512) ?>;

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

        // Lokasi Terdekat
        function haversineDistance(lat1, lon1, lat2, lon2) {
            const toRad = deg => deg * Math.PI / 180;
            const R = 6371; // Earth radius in km
            const dLat = toRad(lat2 - lat1);
            const dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                const sorted = data
                    .filter(dest => dest.latitude && dest.longitude)
                    .map(dest => {
                        const dist = haversineDistance(userLat, userLng, dest.latitude, dest.longitude);
                        return { ...dest, distance: dist.toFixed(2) };
                    })
                    .sort((a, b) => a.distance - b.distance)
                    .slice(0, 3);

                const container = document.getElementById('nearestPlaces');
                if (sorted.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 italic">Tidak ada destinasi dengan koordinat</p>';
                } else {
                    sorted.forEach(dest => {
                        const card = document.createElement('div');
                        card.className = 'border rounded shadow bg-white p-4';
                        card.innerHTML = `
                            <h4 class="text-lg font-semibold mb-1">${dest.name}</h4>
                            <p class="text-sm text-gray-600 mb-1">Jarak: ${dest.distance} km</p>
                            <p class="text-sm text-gray-600 mb-2">${dest.address}</p>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${dest.latitude},${dest.longitude}" 
                               target="_blank" 
                               class="inline-block mt-2 px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                               ➡ Arahkan ke Sini
                            </a>
                        `;
                        container.appendChild(card);
                    });
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ileen\webgis-wisata\resources\views/explore/statistik.blade.php ENDPATH**/ ?>