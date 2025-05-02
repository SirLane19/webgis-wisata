@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] px-6 text-center">
    <h1 class="text-4xl sm:text-5xl font-bold text-blue-700 mb-4">🌟 Selamat Datang di WebGIS Wisata Jakarta</h1>
    <p class="text-gray-600 text-lg max-w-xl mb-6">
        Temukan destinasi menarik di Jakarta melalui peta interaktif dan daftar lokasi lengkap.
        Jelajahi kategori wisata, lihat posisi langsung, dan rencanakan kunjunganmu dengan mudah.
    </p>
    <a href="{{ route('explore') }}"
       class="bg-blue-600 text-white px-6 py-3 rounded text-lg font-semibold hover:bg-blue-700 transition">
        ➤ Mulai Eksplorasi
    </a>
</div>
@endsection
