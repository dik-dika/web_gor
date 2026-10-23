@extends('layouts.front')

@section('content')
<!-- HEADER BANNER -->
<div class="relative bg-indigo-950 text-white py-20 md:py-24 overflow-hidden">
    <!-- Dekorasi Background Glow & Pola Abstrak -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,0 L100,100 M100,0 L0,100" stroke="currentColor" stroke-width="0.5"/>
        </svg>
    </div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10 text-center max-w-3xl">
        <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/40 text-amber-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-4 backdrop-blur-md">
            <span>📍 Lokasi & Layanan</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">
            Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">GOR Raden Ganda</span>
        </h1>
        <p class="text-indigo-200/90 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
            Punya pertanyaan seputar ketersediaan slot lapangan atau ingin booking langsung? Hubungi kami melalui kontak di bawah ini.
        </p>
    </div>
</div>

<!-- KONTEN UTAMA DENGAN BACKGROUND BERTESTUR -->
<div class="bg-gray-50 py-20 relative overflow-hidden">
    <!-- Pola Titik Halus (Dot Pattern) -->
    <div class="absolute inset-0 opacity-40 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="container mx-auto px-6 max-w-6xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KARTU INFORMASI KONTAK -->
            <div class="space-y-6">
                <!-- Alamat Card -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-xl text-indigo-900 mb-4">
                        📍
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">Alamat GOR</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Jl. Raden Ganda II No.95, Sukaraja, Kec. Cicendo, Kota Bandung, Jawa Barat 40175
                    </p>
                </div>

                <!-- WA Card -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-green-50 border border-green-100 rounded-xl flex items-center justify-center text-xl text-green-600 mb-4">
                        📞
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-1">Nomor Telepon / WA</h4>
                    <p class="text-gray-600 text-sm mb-5 font-semibold">+62 821-1584-2709</p>
                    
                    <a href="https://wa.me/6282115842709?text=Halo%20Admin%20GOR%20Raden%20Ganda,%20saya%20mau%20tanya%20slot%20lapangan" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-lg shadow-emerald-600/20 transition-all w-full justify-center">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Chat via WhatsApp
                    </a>
                </div>
            </div>

            <!-- EMBED GOOGLE MAPS -->
            <div class="lg:col-span-2 h-96 lg:h-auto min-h-[400px] bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3790.795315236622!2d107.5580529748187!3d-6.891285467435087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e56a3992ff07%3A0x714afc581c6eae65!2sGOR%20Raden%20Ganda!5e1!3m2!1sen!2sid!4v1781061146208!5m2!1sen!2sid" 
                    class="w-full h-full border-0" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>

        </div>
    </div>
</div>
@endsection