<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Pendapatan GOR') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1 md:mt-0">
                Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('D MMMM Y') }}
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- FILTER & FILTER BENTUK FORM -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tanggalSelesai }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- Tipe Pelanggan (Opsional) -->
                    <div>
                        <label for="tipe_pelanggan" class="block text-sm font-medium text-gray-700">Tipe Pelanggan</label>
                        <select name="tipe_pelanggan" id="tipe_pelanggan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">-- Semua Tipe --</option>
                            <option value="Member" {{ request('tipe_pelanggan') == 'Member' ? 'selected' : '' }}>Member</option>
                            <option value="Non-Member" {{ request('tipe_pelanggan') == 'Non-Member' ? 'selected' : '' }}>Non-Member</option>
                        </select>
                    </div>

                    <!-- Tombol Filter & Print -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md text-sm shadow-sm transition">
                            Filter
                        </button>
                        <button type="button" onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-md text-sm shadow-sm transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak
                        </button>
                    </div>

                </form>
            </div>

            <!-- CARD RINGKASAN REKAPITULASI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Transaksi -->
                <div class="bg-white p-6 shadow sm:rounded-lg border-l-4 border-indigo-500 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi Selesai</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalTransaksi) }} <span class="text-sm font-normal text-gray-500">Booking</span></p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div class="bg-white p-6 shadow sm:rounded-lg border-l-4 border-emerald-500 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TABEL DETAIL LAPORAN -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Rincian Transaksi Booking</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Nama Pemesan</th>
                                <th class="px-4 py-3">Tanggal Main</th>
                                <th class="px-4 py-3">Jam</th>
                                <th class="px-4 py-3">Lapangan</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($laporanBookings as $index => $booking)
                                <tr class="text-gray-700 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-semibold text-gray-900">{{ $booking->nama_pemesan ?? $booking->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->no_hp ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_main)->isoFormat('D MMM Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $booking->jam_mulai ?? '-' }} - {{ $booking->jam_selesai ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $booking->lapangan ?? 'Lapangan 1' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ ($booking->tipe_pelanggan ?? 'Non-Member') == 'Member' ? 'text-indigo-700 bg-indigo-100' : 'text-gray-700 bg-gray-100' }}">
                                            {{ $booking->tipe_pelanggan ?? 'Non-Member' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900">
                                        Rp {{ number_format($booking->total_harga ?? 50000, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 text-sm">
                                        Tidak ada data transaksi disetujui/selesai dalam rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($laporanBookings->count() > 0)
                            <tfoot>
                                <tr class="bg-gray-50 font-bold text-gray-900 border-t">
                                    <td colspan="6" class="px-4 py-3 text-right uppercase text-xs">Total Pendapatan:</td>
                                    <td class="px-4 py-3 text-right text-emerald-600">
                                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- STYLES KHUSUS UNTUK CETAK (PRINT) -->
    <style>
        @media print {
            /* Sembunyikan Navigasi & Filter saat dicetak */
            nav, header, form, button {
                display: none !important;
            }
            body {
                background-color: #fff !important;
            }
            .shadow, .shadow-sm {
                box-shadow: none !important;
            }
            .border-l-4 {
                border-left: none !important;
            }
        }
    </style>
</x-app-layout>