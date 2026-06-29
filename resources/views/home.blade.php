@extends('layouts.front')

@section('content')
<div class="bg-indigo-900 text-white py-24 px-6 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Smash Prestasisimu di GOR Raden Ganda!</h1>
    <p class="text-lg md:text-xl text-indigo-200 mb-8 max-w-2xl mx-auto">
        Nikmati fasilitas lapangan badminton standar komunitas dengan pencahayaan maksimal dan suasana yang nyaman.
    </p>
    <a href="{{ route('kontak') }}" class="bg-amber-500 hover:bg-amber-600 text-indigo-950 font-bold px-8 py-3 rounded-lg shadow-lg transition">
        Sewa Lapangan Sekarang
    </a>
</div>

<div class="container mx-auto px-6 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Mengapa Memilih Kami?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
            <div class="text-4xl mb-4">🏸</div>
            <h3 class="text-xl font-bold mb-2">Lapangan Berkualitas</h3>
            <p class="text-gray-600">Lantai lapangan yang nyaman dan tidak licin, meminimalisir risiko cedera.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
            <div class="text-4xl mb-4">💡</div>
            <h3 class="text-xl font-bold mb-2">Pencahayaan Terang</h3>
            <p class="text-gray-600">Sistem penerangan LED yang merata, tidak silau saat melihat kok ke atas.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
            <div class="text-4xl mb-4">🚗</div>
            <h3 class="text-xl font-bold mb-2">Parkir Luas & Aman</h3>
            <p class="text-gray-600">Area parkir yang memadai untuk motor tanpa mengganggu jalan umum.</p>
        </div>
    </div>
</div>
@endsection