

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <h2 class="text-2xl font-bold">Daftar Destinasi</h2>
        <a href="<?php echo e(route('destinations.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('destinations.index')); ?>" class="mb-6 flex flex-col sm:flex-row sm:items-center sm:flex-wrap gap-2 sm:gap-4">
        <input type="text" name="search" placeholder="Cari destinasi..." value="<?php echo e(request('search')); ?>"
            class="border p-2 rounded w-full sm:w-1/4" />

        <select name="category" class="border p-2 rounded w-full sm:w-1/4">
            <option value="">-- Semua Kategori --</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>>
                    <?php echo e($cat->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <select name="sort" class="border p-2 rounded w-full sm:w-1/5">
            <option value="">-- Urutkan Berdasarkan --</option>
            <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Nama</option>
            <option value="category" <?php echo e(request('sort') == 'category' ? 'selected' : ''); ?>>Kategori</option>
        </select>

        <select name="order" class="border p-2 rounded w-full sm:w-1/5">
            <option value="asc" <?php echo e(request('order') == 'asc' ? 'selected' : ''); ?>>A-Z</option>
            <option value="desc" <?php echo e(request('order') == 'desc' ? 'selected' : ''); ?>>Z-A</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan</button>
            <a href="<?php echo e(route('destinations.index')); ?>" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
        </div>
    </form>

    
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
            <?php $__empty_1 = true; $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
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
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2"><?php echo e($dest->name); ?></td>
                    <td class="px-4 py-2"><?php echo e($dest->address); ?></td>
                    <td class="px-4 py-2"><?php echo e($dest->category->name ?? '-'); ?></td>
                    <td class="px-4 py-2"><?php echo e($dest->latitude); ?></td>
                    <td class="px-4 py-2"><?php echo e($dest->longitude); ?></td>
                    <td class="px-4 py-2">
                        <?php if($dest->photo): ?>
                            <img src="<?php echo e(asset('storage/photos/' . $dest->photo)); ?>" alt="<?php echo e($dest->name); ?>" class="h-16 object-cover mx-auto rounded" onerror="this.onerror=null;this.src='<?php echo e(asset('img/default.png')); ?>';">
                        <?php else: ?>
                            <span class="text-gray-400 italic">Tidak ada foto</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2">
                        <?php echo e($isOpenToday && $isOpenToday->day ? ucfirst($isOpenToday->day) : '-'); ?>

                    </td>
                    <td class="px-4 py-2">
                        <?php echo e($isOpenToday && $isOpenToday->open_time ? \Carbon\Carbon::parse($isOpenToday->open_time)->format('H:i') : '-'); ?>

                    </td>
                    <td class="px-4 py-2">
                        <?php echo e($isOpenToday && $isOpenToday->close_time ? \Carbon\Carbon::parse($isOpenToday->close_time)->format('H:i') : '-'); ?>

                    </td>
                    <td class="px-4 py-2 font-semibold <?php echo e($isOpen ? 'text-green-600' : 'text-red-500'); ?>">
                        <?php echo e($isOpen ? 'Buka' : 'Tutup'); ?>

                    </td>
                    <td class="px-4 py-2">
                        <div class="flex flex-col sm:flex-row justify-center gap-1 sm:gap-3">
                            <a href="<?php echo e(route('destinations.edit', $dest->id)); ?>" class="text-blue-600 hover:underline">Edit</a>
                            <form action="<?php echo e(route('destinations.destroy', $dest->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11" class="text-center text-gray-500 py-6">Belum ada destinasi yang ditambahkan.</td>
                </tr>
            <?php endif; ?>
</tbody>
        </table>
    </div>

    <div class="mt-6">
        <?php echo e($destinations->links()); ?>

    </div>

    <div class="mt-6">
        <a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
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

        <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($dest->latitude && $dest->longitude): ?>
                L.marker([<?php echo e($dest->latitude); ?>, <?php echo e($dest->longitude); ?>])
                    .addTo(map)
                    .bindPopup(`<strong><?php echo e($dest->name); ?></strong><br><?php echo e($dest->address); ?><br><em><?php echo e($dest->category->name ?? '-'); ?></em>`);
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ileen\webgis-wisata\resources\views/destinations/index.blade.php ENDPATH**/ ?>