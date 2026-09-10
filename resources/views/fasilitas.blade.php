@extends('layouts.front')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h1 class="text-3xl font-bold text-indigo-900 mb-4">Fasilitas GOR Raden Ganda</h1>
        <p class="text-gray-600">Kami menyediakan fasilitas terbaik demi kenyamanan dan kepuasan bertanding Anda bersama teman maupun komunitas.</p>
    </div>

    <!-- Cuma butuh 1 pembungkus grid di paling luar -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">

        <!-- KARTU 1: Lapangan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <img
                src="{{ asset('img/lapang.jpeg') }}"
                alt="1 Lapangan Standar"
                class="w-full h-64 object-cover" />

            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Lapangan</h3>
                <p class="text-gray-600">Lapangan non karpet standar</p>
            </div>
        </div>

        <!-- KARTU 2: Tempat Parkir (Dimasukkan ke sini, dalam grid yang sama) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <img
                src="{{ asset('img/parkir.jpeg') }}"
                alt="Tempat Parkir"
                class="w-full h-64 object-cover" />

            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Tempat Parkir</h3>
                <p class="text-gray-600">Tempat parkir yang luas dan aman untuk kendaraan Anda.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <!-- Ganti emoji dengan elemen gambar ini -->
            <img
                src="{{ asset('img/parkir.jpeg') }}"
                alt="1 Lapangan Standar"
                class="w-full h-64 object-cover" />

            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Kantin</h3>
                <p class="text-gray-600">Kantin yang nyaman dan menyediakan berbagai macam makanan dan minuman.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <!-- Ganti emoji dengan elemen gambar ini -->
            <img
                src="{{ asset('img/toilet.jpeg') }}"
                alt="1 Lapangan Standar"
                class="w-full h-64 object-cover" />

            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Toilet</h3>
                <p class="text-gray-600">Toilet yang bersih dan nyaman untuk kenyamanan Anda.</p>
            </div>
        </div>
    </div>
</div> <!-- Tag penutup grid cukup 1 saja di akhir -->

@endsection