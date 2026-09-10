@extends('layouts.front')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h1 class="text-3xl font-bold text-indigo-900 mb-4">Hubungi GOR Raden Ganda</h1>
        <p class="text-gray-600">Punya pertanyaan seputar ketersediaan slot lapangan atau ingin booking langsung? Hubungi kami melalui kontak di bawah ini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-indigo-900 mb-1">📍 Alamat GOR</h4>
                <p class="text-gray-600 text-sm">Jl. Raden Ganda II No.95, Sukaraja, Kec. Cicendo, Kota Bandung, Jawa Barat 40175</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h4 class="font-bold text-indigo-900 mb-1">📞 Nomor Telepon / WA</h4>
                <p class="text-gray-600 text-sm mb-4">+62 821-1584-2709</p>
                <a href="https://wa.me/6282115842709?text=Halo%20Admin%20GOR%20Raden%20Ganda,%20saya%20mau%20tanya%20slot%20lapangan" 
                   target="_blank" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-4 py-2 rounded-lg shadow transition">
                    Chat via WhatsApp
                </a>
            </div>

            
        </div>

        <div class="lg:col-span-2 h-96 lg:h-auto min-h-[350px] bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3790.795315236622!2d107.5580529748187!3d-6.891285467435087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e56a3992ff07%3A0x714afc581c6eae65!2sGOR%20Raden%20Ganda!5e1!3m2!1sen!2sid!4v1781061146208!5m2!1sen!2sid" 
                class="w-full h-full border-0" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>
@endsection