@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-bold mb-6">Selamat datang, {{ Auth::user()->name }}!</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white border rounded shadow p-4 text-center">
            <h3 class="text-lg font-semibold">Total Destinasi</h3>
            <p class="text-3xl text-blue-600 mt-2">{{ $totalDestinations }}</p>
        </div>
        <div class="bg-white border rounded shadow p-4 text-center">
            <h3 class="text-lg font-semibold">Total Kategori</h3>
            <p class="text-3xl text-green-600 mt-2">{{ $totalCategories }}</p>
        </div>
    </div>

    {{-- Tombol Akses ke Kelola Destinasi --}}
    <div class="flex justify-center gap-4 mt-6">
        <a href="{{ route('destinations.index') }}" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <span>+</span> Kelola Destinasi
        </a>
        <a href="{{ url('/explore') }}" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Lihat Halaman Explore
        </a>
    </div>
</div>
@endsection
