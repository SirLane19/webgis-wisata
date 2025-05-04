@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0a192f] text-white flex flex-col md:flex-row items-center justify-between px-8 md:px-20 py-16 relative overflow-hidden">

    <!-- Left Section -->
    <div class="w-full md:w-1/2 z-10 space-y-6">
        <h1 class="text-4xl md:text-5xl font-bold leading-snug">
            JELAJAHI WISATA JAKARTA<br>
            <span class="text-[#3ABAF8]">dengan WebGIS Interaktif</span>
        </h1>
        <p class="text-blue-200 text-lg max-w-md">
            Temukan berbagai destinasi menarik melalui peta digital. Lihat lokasi langsung, jelajahi kategori, dan rencanakan perjalananmu dengan mudah!
        </p>
        <a href="{{ route('explore') }}"
           class="inline-block bg-gradient-to-r from-[#3ABAF8] to-blue-400 hover:from-blue-400 hover:to-[#3ABAF8] text-[#0a192f] font-bold px-6 py-3 rounded-lg shadow-md transition">
            Mulai Eksplorasi
        </a>
    </div>

    <!-- Right Section -->
    <div class="w-full md:w-1/2 z-10 mt-12 md:mt-0">
        <img src="{{ asset('images/fun-webgis.png') }}" alt="Ilustrasi WebGIS" class="w-full max-w-md mx-auto drop-shadow-xl rounded-xl">
    </div>

    <!-- Decorative Background Circle -->
    <div class="absolute top-[-50px] right-[-100px] w-[300px] h-[300px] bg-[#3ABAF8] opacity-20 rounded-full blur-3xl z-0"></div>

    <!-- Optional Scroll Text -->
    <div class="absolute right-6 bottom-6 text-xs tracking-widest text-blue-300 rotate-90 hidden md:block">
        SCROLL ➤
    </div>
</div>
@endsection
