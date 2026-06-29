@extends('layouts.front')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h1 class="text-3xl font-bold text-indigo-900 mb-4">Fasilitas GOR Raden Ganda</h1>
        <p class="text-gray-600">Kami menyediakan fasilitas terbaik demi kenyamanan dan kepuasan bertanding Anda bersama teman maupun komunitas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="h-64 bg-indigo-200 flex items-center justify-center text-6xl">🏸</div> 
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">1 Lapangan Standar</h3>
                <p class="text-gray-600">Lantai dilapisi karpet vinyl berkualitas tinggi yang empuk, tidak licin, dan standar turnamen untuk menjaga keamanan sendi Anda saat melakukan *jumping smash*.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="h-64 bg-amber-100 flex items-center justify-center text-6xl">🚿</div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Kamar Mandi & Ruang Ganti Clean</h3>
                <p class="text-gray-600">Tersedia kamar mandi yang bersih dan nyaman untuk bilas atau berganti pakaian sebelum dan sesudah berolahraga.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="h-64 bg-teal-100 flex items-center justify-center text-6xl">🕌</div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Musholla Terpisah</h3>
                <p class="text-gray-600">Bagi Anda yang bermain di waktu shalat, kami menyediakan ruang ibadah yang tenang.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="h-64 bg-orange-100 flex items-center justify-center text-6xl">🥤</div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2">Kantin & Rest Area</h3>
                <p class="text-gray-600">Kehabisan minum atau butuh energi tambahan? Kantin kami menyediakan berbagai minuman dingin, makanan ringan, hingga kok (*shuttlecock*) cadangan.</p>
            </div>
        </div>
    </div>
</div>
@endsection