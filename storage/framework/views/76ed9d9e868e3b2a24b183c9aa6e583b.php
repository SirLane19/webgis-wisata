

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Destinasi Wisata</h2>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
            <ul class="list-disc pl-4 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('destinations.update', $destination->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div>
            <label class="block font-medium mb-1">Nama</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="<?php echo e(old('name', $destination->name)); ?>" required>
        </div>

        
        <div>
            <label class="block font-medium mb-1">Alamat</label>
            <input type="text" name="address" class="w-full border rounded p-2" value="<?php echo e(old('address', $destination->address)); ?>" required>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Latitude</label>
                <input type="text" name="latitude" class="w-full border rounded p-2" value="<?php echo e(old('latitude', $destination->latitude)); ?>" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Longitude</label>
                <input type="text" name="longitude" class="w-full border rounded p-2" value="<?php echo e(old('longitude', $destination->longitude)); ?>" required>
            </div>
        </div>

        
        <div>
            <label class="block font-medium mb-1">Harga Tiket</label>
            <input type="text" name="ticket_price" class="w-full border rounded p-2" value="<?php echo e(old('ticket_price', $destination->ticket_price)); ?>">
        </div>

        
        <div>
            <label class="block font-medium mb-1">Kategori</label>
            <select name="category_id" required class="w-full border rounded p-2">
                <option value="">-- Pilih Kategori --</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $destination->category_id ?? '') == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div>
            <label class="block font-medium mb-1">Foto Baru (opsional)</label>
            <input type="file" name="photo" accept="image/*" class="w-full border p-2 rounded">
            <?php if($destination->photo): ?>
                <p class="text-sm text-gray-600 mt-2">Foto saat ini:</p>
                <img src="<?php echo e(asset('storage/photos/' . $destination->photo)); ?>" alt="Foto destinasi" class="w-32 rounded shadow mt-1">
            <?php endif; ?>
        </div>

        
        <div>
            <label class="block font-medium mb-2">Jadwal Buka (Hari dan Jam)</label>
            <div id="schedule-container" class="flex flex-col gap-4">
                <?php $__currentLoopData = $destination->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Hari</label>
                            <select name="schedules[<?php echo e($i); ?>][day]" class="w-full border p-2 rounded" required>
                                <?php $__currentLoopData = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($day); ?>" <?php echo e($s->day === $day ? 'selected' : ''); ?>>
                                        <?php echo e(ucfirst($day)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Buka</label>
                            <input type="time" name="schedules[<?php echo e($i); ?>][open_time]" value="<?php echo e($s->open_time); ?>" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jam Tutup</label>
                            <input type="time" name="schedules[<?php echo e($i); ?>][close_time]" value="<?php echo e($s->close_time); ?>" class="w-full border p-2 rounded" required>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="flex justify-between items-center pt-4">
            <a href="<?php echo e(route('destinations.index')); ?>" class="text-blue-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Update</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ileen\webgis-wisata\resources\views/destinations/edit.blade.php ENDPATH**/ ?>