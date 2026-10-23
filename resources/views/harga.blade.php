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
            <span>💰 Tarif & Jadwal</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">
            Daftar Harga & <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200">Jam Operasional</span>
        </h1>
        <p class="text-indigo-200/90 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto">
            Pilih waktu terbaik untuk jadwal latihan rutin Anda atau komunitas bersama GOR Raden Ganda.
        </p>
    </div>
</div>

<!-- KONTEN UTAMA DENGAN BACKGROUND BERTESTUR -->
<div class="bg-gray-50 py-20 relative overflow-hidden">
    <!-- Pola Titik Halus (Dot Pattern) -->
    <div class="absolute inset-0 opacity-40 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="container mx-auto px-6 max-w-4xl relative z-10">
        
        <!-- JAM OPERASIONAL CARD -->
        <div class="bg-gradient-to-r from-indigo-900 to-indigo-950 text-white p-8 rounded-2xl shadow-xl flex flex-col md:flex-row items-center justify-between mb-12 border border-indigo-800">
            <div class="flex items-center gap-4 mb-4 md:mb-0">
                <div class="w-14 h-14 bg-amber-500/20 border border-amber-400/40 text-amber-400 rounded-xl flex items-center justify-center text-2xl">
                    🕒
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-amber-400 mb-1">Jam Operasional GOR</h3>
                    <p class="text-2xl font-black">Setiap Hari: 08.00 - 23.00 WIB</p>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN HARGA -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-8">
            <div class="bg-gray-900 text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rincian Tarif Sewa Lapangan
                </h3>
                <span class="text-xs bg-indigo-900 text-indigo-200 px-3 py-1 rounded-full border border-indigo-700">Resmi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-5 font-bold">Kategori / Hari</th>
                            <th class="p-5 font-bold">Keterangan Waktu</th>
                            <th class="p-5 font-bold text-right">Tarif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <!-- Reguler -->
                        <tr class="hover:bg-indigo-50/50 transition-colors">
                            <td class="p-5 font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                Reguler (Senin - Minggu)
                            </td>
                            <td class="p-5 text-gray-600">08:00 - 23:00 WIB</td>
                            <td class="p-5 font-extrabold text-indigo-900 text-right text-lg">Rp 30.000 <span class="text-xs font-normal text-gray-500">/ jam</span></td>
                        </tr>
                        <!-- Member 3 Jam -->
                        <tr class="hover:bg-indigo-50/50 transition-colors">
                            <td class="p-5 font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                Paket Member Hemat
                            </td>
                            <td class="p-5 text-gray-600">3 jam / minggu</td>
                            <td class="p-5 font-extrabold text-amber-600 text-right text-lg">Rp 200.000 <span class="text-xs font-normal text-gray-500">/ bulan</span></td>
                        </tr>
                        <!-- Member 5 Jam -->
                        <tr class="hover:bg-indigo-50/50 transition-colors">
                            <td class="p-5 font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                Paket Member Reguler
                            </td>
                            <td class="p-5 text-gray-600">5 jam / minggu</td>
                            <td class="p-5 font-extrabold text-amber-600 text-right text-lg">Rp 250.000 <span class="text-xs font-normal text-gray-500">/ bulan</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CATATAN -->
        <div class="bg-amber-50 border-l-4 border-amber-500 rounded-r-xl p-5 shadow-sm flex items-start gap-4">
            <div class="text-amber-600 text-xl font-bold">💡</div>
            <div>
                <h4 class="font-bold text-amber-900 mb-1">Catatan Pendaftaran Member</h4>
                <p class="text-sm text-amber-800 leading-relaxed">Untuk pendaftaran paket member bulanan, silakan datang langsung ke lokasi GOR Raden Ganda atau hubungi admin melalui halaman kontak.</p>
            </div>
        </div>

    </div>
</div>
@endsection