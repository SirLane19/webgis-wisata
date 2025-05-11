

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-center sm:text-left text-gray-800">🤝 Eksplorasi Destinasi Wisata</h1>
        <a href="<?php echo e(route('statistik')); ?>" class="inline-block bg-gradient-to-r from-green-500 to-green-700 text-white px-5 py-2 rounded-full shadow hover:brightness-110 transition">📊 Lihat Statistik</a>
    </div>

    
    <div class="mb-10">
        <h2 class="text-xl font-semibold mb-3 text-gray-700">Peta Lokasi</h2>
        <div id="map" class="w-full h-[400px] rounded shadow border"></div>
    </div>

    
    <form method="GET" action="<?php echo e(route('explore')); ?>" class="mb-10 p-4 bg-white shadow rounded grid gap-3 sm:grid-cols-3">
        <input type="text" name="search" placeholder="Cari nama destinasi..." value="<?php echo e(request('search')); ?>"
            class="border p-2 rounded w-full focus:ring focus:ring-blue-200" />

        <select name="category" class="border p-2 rounded w-full focus:ring focus:ring-blue-200">
            <option value="">-- Semua Kategori --</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>>
                    <?php echo e($cat->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition w-full">🔎 Cari</button>
    </form>

    
    <div>
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Hasil Destinasi</h2>
        <p class="text-sm text-gray-600 mb-2">
            Menampilkan <?php echo e($destinations->firstItem()); ?> - <?php echo e($destinations->lastItem()); ?> dari <?php echo e($destinations->total()); ?> destinasi
        </p>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded-xl p-4 shadow-md hover:shadow-lg transition bg-white">
                    <div class="mb-3">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo e($dest->name); ?></h3>
                        <p class="text-sm text-blue-500"><?php echo e($dest->category->name ?? '-'); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($dest->address); ?></p>
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            type="button"
                            class="text-sm text-blue-600 hover:underline lihat-detail"
                            data-name="<?php echo e($dest->name); ?>"
                            data-category="<?php echo e($dest->category->name ?? '-'); ?>"
                            data-address="<?php echo e($dest->address); ?>"
                            data-ticket="<?php echo e($dest->ticket_price ?? 'Gratis / Tidak disebutkan'); ?>"
                            data-latitude="<?php echo e($dest->latitude); ?>"
                            data-longitude="<?php echo e($dest->longitude); ?>"
                            data-photo="<?php echo e(asset('storage/photos/' . $dest->photo)); ?>">
                            🔍 Detail
                        </button>
                        <button class="favorite-btn" data-id="<?php echo e($dest->id); ?>">
                            <span class="text-xl transition" id="fav-icon-<?php echo e($dest->id); ?>">🧥</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500 col-span-3">
                    Tidak ada destinasi ditemukan.
                    <a href="<?php echo e(route('explore')); ?>" class="text-blue-500 hover:underline">Reset filter?</a>
                </p>
            <?php endif; ?>
        </div>

        
        <div class="mt-10 flex justify-center">
            <?php echo e($destinations->onEachSide(1)->withQueryString()->links()); ?>

        </div>
    </div>
</div>


<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[1000]">
    <div class="flex items-center justify-center min-h-screen w-full">
        <div class="bg-white z-[1001] p-6 rounded-xl shadow-lg max-w-md w-full relative transition transform duration-300 scale-95">
            <button onclick="tutupModal()" class="absolute top-3 right-4 text-gray-500 hover:text-black text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-2" id="modalName"></h2>
            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Kategori:</span> <span id="modalCategory"></span></p>
            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Alamat:</span> <span id="modalAddress"></span></p>
            <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">Tiket:</span> <span id="modalTicket"></span></p>
            <img id="modalPhoto" class="w-full mt-3 rounded shadow" alt="Gambar destinasi">
        </div>
    </div>
</div>


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
                icon.textContent = '🧥';
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

        const data = <?php echo json_encode($destinationArray, 15, 512) ?>;

        data.forEach(dest => {
            if (dest.latitude && dest.longitude) {
                L.marker([dest.latitude, dest.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div style="max-width: 250px;">
                            <strong>${dest.name}</strong><br>
                            <em>Kategori: ${dest.category}</em><br>
                            Alamat: ${dest.address}<br>
                            Tiket: ${dest.ticket_price ?? 'Gratis / Tidak disebutkan'}<br>
                            <img src="${dest.photo}" 
                                 alt="${dest.name}" 
                                 style="margin-top: 8px; width: 100%; height: auto; border-radius: 8px;" 
                                 onerror="this.onerror=null;this.src='/img/default.png';">
                        </div>
                    `);
            }
        });

        document.querySelectorAll('.lihat-detail').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalName').innerText = this.dataset.name;
                document.getElementById('modalCategory').innerText = this.dataset.category;
                document.getElementById('modalAddress').innerText = this.dataset.address;
                document.getElementById('modalTicket').innerText = this.dataset.ticket;
                document.getElementById('modalPhoto').src = this.dataset.photo;
                bukaModal();

                const lat = parseFloat(this.dataset.latitude);
                const lng = parseFloat(this.dataset.longitude);
                if (lat && lng) showRouteToDestination(lat, lng);
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ileen\webgis-wisata\resources\views/explore/index.blade.php ENDPATH**/ ?>