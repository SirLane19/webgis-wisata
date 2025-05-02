@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📊 Dashboard Admin</h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded shadow p-6 border">
            <h2 class="text-sm text-gray-500 uppercase mb-1">Total Destinasi</h2>
            <p class="text-3xl font-bold text-blue-600">{{ $totalDestinations }}</p>
        </div>

        <div class="bg-white rounded shadow p-6 border">
            <h2 class="text-sm text-gray-500 uppercase mb-1">Kategori Aktif</h2>
            <p class="text-3xl font-bold text-green-600">{{ $totalCategories }}</p>
        </div>
    </div>

    <div class="flex gap-4 flex-wrap">
        <a href="{{ route('destinations.create') }}"
           class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
            ➕ Tambah Destinasi Baru
        </a>

        <a href="{{ route('explore') }}"
           class="bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300 transition">
            🌍 Lihat Halaman Publik
        </a>
    </div>
</div>
@endsection
