@extends('layouts.front')

@section('content')
<!-- HEADER BANNER DENGAN EFEK VISUAL & GRADIENT -->
<div class="relative bg-indigo-950 text-white py-20 md:py-24 overflow-hidden">
    <!-- Dekorasi Background Glow & Pola Abstrak -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,0 L100,100 M100,0 L0,100" stroke="currentColor" stroke-width="0.5"/>
        </svg>
    </div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10 text-center max-w-3xl">
        <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/40 text-amber-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-4 backdrop-blur-md">
            <span>🏸 Sarana & Prasarana</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">
            Fasilitas Terbaik di <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">GOR Raden Ganda</span>
        </h1>
        <p class="text-indigo-200/90 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
            Kami menyediakan fasilitas terbaik demi kenyamanan, keamanan, dan kepuasan bertanding Anda bersama teman maupun komunitas.
        </p>
    </div>
</div>

<!-- KONTEN KARTU FASILITAS DENGAN BACKGROUND BERTESTUR -->
<div class="bg-gray-50 py-20 relative overflow-hidden">
    <!-- Pola Titik Halus (Dot Pattern) agar tidak polos -->
    <div class="absolute inset-0 opacity-40 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Grid Kartu Fasilitas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- KARTU 1: Lapangan -->
            <div class="group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="overflow-hidden relative h-64">
                    <img src="{{ asset('img/lapang.jpeg') }}" 
                         alt="Lapangan Badminton" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                    <div class="absolute top-4 left-4 bg-indigo-900/80 backdrop-blur-md text-white px-3 py-1 rounded-lg text-xs font-semibold shadow-md">
                        Area Utama
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Lapangan Badminton</h3>
                    <p class="text-gray-600 leading-relaxed">Lapangan dengan standar komunitas yang nyaman dan terawat, siap mendukung performa permainan terbaik Anda.</p>
                </div>
            </div>

            <!-- KARTU 2: Tempat Parkir -->
            <div class="group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="overflow-hidden relative h-64">
                    <img src="{{ asset('img/parkir.jpeg') }}" 
                         alt="Tempat Parkir" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                    <div class="absolute top-4 left-4 bg-indigo-900/80 backdrop-blur-md text-white px-3 py-1 rounded-lg text-xs font-semibold shadow-md">
                        Area Luar
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Tempat Parkir Luas</h3>
                    <p class="text-gray-600 leading-relaxed">Area parkir kendaraan roda dua dan roda empat yang luas, tertata, serta aman untuk kenyamanan pengunjung.</p>
                </div>
            </div>

            <!-- KARTU 3: Kantin -->
            <div class="group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="overflow-hidden relative h-64">
                    <img src="{{ asset('img/kantin.jpeg') }}" 
                         alt="Kantin GOR" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                    <div class="absolute top-4 left-4 bg-indigo-900/80 backdrop-blur-md text-white px-3 py-1 rounded-lg text-xs font-semibold shadow-md">
                        Konsumsi
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Kantin & Rest Area</h3>
                    <p class="text-gray-600 leading-relaxed">Menyediakan berbagai macam makanan ringan dan minuman segar untuk mengisi kembali energi setelah lelah bertanding.</p>
                </div>
            </div>

            <!-- KARTU 4: Toilet -->
            <div class="group bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="overflow-hidden relative h-64">
                    <img src="{{ asset('img/toilet.jpeg') }}" 
                         alt="Toilet Bersih" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                    <div class="absolute top-4 left-4 bg-indigo-900/80 backdrop-blur-md text-white px-3 py-1 rounded-lg text-xs font-semibold shadow-md">
                        Kebersihan
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Toilet Bersih & Nyaman</h3>
                    <p class="text-gray-600 leading-relaxed">Fasilitas toilet yang selalu dijaga kebersihannya demi kenyamanan pengunjung selama berada di area GOR.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection