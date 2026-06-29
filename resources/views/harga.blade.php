@extends('layouts.front')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-4xl">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-indigo-900 mb-4">Daftar Harga & Jam Operasional</h1>
        <p class="text-gray-600">Pilih waktu terbaik untuk jadwal latihan rutin Anda atau komunitas.</p>
    </div>

    <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-md text-center mb-10">
        <h3 class="text-lg font-semibold text-amber-400 mb-1">JAM OPERASIONAL</h3>
        <p class="text-2xl font-bold">Setiap Hari: 08.00 - 23.00 WIB</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th class="p-4 font-bold text-gray-700">Hari</th>
                    <th class="p-4 font-bold text-gray-700">Waktu (WIB)</th>
                    <th class="p-4 font-bold text-gray-700 text-right">Tarif / Jam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="p-4 font-low" rowspan="2">Senin - Minggu </td>
                    <td class="p-4 text-gray-600"> 08.00 - 23.00</td>
                    <td class="p-4 text-right font-bold text-indigo-600">Rp 30.000</td>
                </tr>
            </tbody>
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th class="p-4 font-bold text-gray-700">Member</th>
                    <th class="p-4 text-gray-700">3jam / Minggu</th>
                    <th class="p-4 font-bold text-gray-700 text-right text-indigo-600">Rp 200.000</th>
                </tr>
            </thead>
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200">
                    <th class="p-4 font-bold text-gray-700">Member</th>
                    <th class="p-4 text-gray-700">5jam / Minggu</th>
                    <th class="p-4 font-bold text-gray-700 text-right text-indigo-600">Rp 250.000</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
        <span class="font-bold">Catatan:</span> Untuk pendaftaran member bisa langsung datang ke tempat.
    </div>
</div>
@endsection 