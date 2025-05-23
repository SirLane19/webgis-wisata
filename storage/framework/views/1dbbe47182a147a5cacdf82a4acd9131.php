

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Tambah Destinasi Wisata</h2>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <strong>Oops!</strong> Ada kesalahan pada input kamu:
            <ul class="list-disc ml-5 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('destinations.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded space-y-4">
        <?php echo csrf_field(); ?>

        <div>
            <label class="block font-semibold mb-1">Nama Destinasi</label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block font-semibold mb-1">Alamat</label>
            <input type="text" name="address" value="<?php echo e(old('address')); ?>" required class="w-full border p-2 rounded">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Latitude</label>
                <input type="text" name="latitude" value="<?php echo e(old('latitude')); ?>" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-1">Longitude</label>
                <input type="text" name="longitude" value="<?php echo e(old('longitude')); ?>" required class="w-full border p-2 rounded">
            </div>
        </div>

        <div>
            <label class="block font-semibold mb-1">Harga Tiket</label>
            <input type="text" name="ticket_price" value="<?php echo e(old('ticket_price')); ?>" class="w-full border p-2 rounded">
        </div>

        
        <div>
            <label class="block font-semibold mb-1">Kategori</label>
            <select name="category_id" required class="...">
                <option value="">-- Pilih Kategori --</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select> 
        </div>

        <div>
            <label class="block font-semibold mb-1">Foto Destinasi</label>
            <input type="file" name="photo" accept="image/*" class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-between items-center mt-6">
            <a href="<?php echo e(route('destinations.index')); ?>" class="text-gray-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ileen\webgis-wisata\resources\views/destinations/create.blade.php ENDPATH**/ ?>