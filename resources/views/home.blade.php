@extends('layouts.front')

@section('content')
<!-- HERO SECTION DENGAN BACKGROUND FOTO & OVERLAY GRADIENT -->
<div class="relative bg-indigo-950 text-white overflow-hidden">
    <!-- Gambar Latar Badminton dengan Overlay Soft Dark -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=1600&auto=format&fit=crop" 
             alt="Lapangan Badminton" 
             class="w-full h-full object-cover object-center opacity-30 scale-105 transform hover:scale-100 transition duration-1000">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-950 via-indigo-900/90 to-transparent"></div>
    </div>

    <!-- Konten Utama Hero Section -->
    <div class="relative z-10 container mx-auto px-6 py-28 md:py-36 flex flex-col items-start">
        <!-- Badge Status GOR -->
        <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/40 text-amber-300 px-3 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-6 backdrop-blur-md">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            GOR Badminton Standar Komunitas
        </div>

        <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6 max-w-3xl tracking-tight">
            Smash Prestasisimu di <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">GOR Raden Ganda!</span>
        </h1>
        
        <p class="text-lg md:text-xl text-indigo-100/90 mb-10 max-w-2xl leading-relaxed">
            Rasakan keseruan bertanding dengan fasilitas lapangan badminton karpet vinyl berkualitas, pencahayaan maksimal anti-silau, dan suasana yang sporty.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-400 text-indigo-950 font-bold px-8 py-4 rounded-xl shadow-xl shadow-amber-500/20 transition-all transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Sewa Lapangan Sekarang
            </a>
            <a href="{{ route('fasilitas') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl backdrop-blur-md transition-all border border-white/20">
                Lihat Fasilitas
            </a>
        </div>
    </div>
</div>

<!-- SECTION MENGAPA MEMILIH KAMI -->
<div class="bg-gray-50 relative py-20 overflow-hidden">
    <!-- Aksen Garis Lapangan Badminton (Background Watermark) -->
    <div class="absolute -top-10 -right-10 opacity-5 pointer-events-none">
        <svg width="400" height="400" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1" class="text-indigo-900">
            <rect x="10" y="10" width="80" height="80" />
            <line x1="10" y1="50" x2="90" y2="50" />
            <line x1="50" y1="10" x2="50" y2="90" />
        </svg>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-indigo-950 mb-3">Mengapa Memilih Kami?</h2>
            <p class="text-gray-600">Dukungan sarana terbaik untuk performa bulutangkis maksimal Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Kartu 1: Lapangan -->
            <div class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-bl-full transition-all group-hover:scale-110"></div>
                <div class="w-14 h-14 bg-indigo-50 text-indigo-900 rounded-xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-amber-500 group-hover:text-indigo-950 transition-colors">
                    🏸
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Lapangan Karpet Standar</h3>
                <p class="text-gray-600 leading-relaxed">Lantai lapangan dilapisi karpet khusus badminton anti-licin untuk kenyamanan pergerakan dan meminimalisir cedera.</p>
            </div>

            <!-- Kartu 2: Lampu -->
            <div class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-bl-full transition-all group-hover:scale-110"></div>
                <div class="w-14 h-14 bg-indigo-50 text-indigo-900 rounded-xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-amber-500 group-hover:text-indigo-950 transition-colors">
                    💡
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Pencahayaan LED Terang</h3>
                <p class="text-gray-600 leading-relaxed">Lampu LED High-Bay dengan pencahayaan merata ke seluruh sudut lapangan, tidak menyilaukan mata saat *smash* atau *lob*.</p>
            </div>

            <!-- Kartu 3: Parkir -->
            <div class="group bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-bl-full transition-all group-hover:scale-110"></div>
                <div class="w-14 h-14 bg-indigo-50 text-indigo-900 rounded-xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:bg-amber-500 group-hover:text-indigo-950 transition-colors">
                    🚗
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Parkir Luas & Aman</h3>
                <p class="text-gray-600 leading-relaxed">Area parkir khusus kendaraan roda dua dan roda empat yang aman dan tertata rapi tanpa mengganggu lalu lintas umum.</p>
            </div>
        </div>
    </div>
</div>
@endsection