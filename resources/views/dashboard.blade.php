<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pemilik GOR Raden Ganda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Notification -->
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card: Booking Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Booking Hari Ini</p>
                        <h4 class="text-3xl font-bold text-indigo-900 mt-1">{{ $bookingHariIni }} Tim</h4>
                    </div>
                    <span class="text-3xl">🏸</span>
                </div>

                <!-- Card: Permintaan Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Permintaan Pending</p>
                        <h4 class="text-3xl font-bold text-amber-600 mt-1">{{ $bookingPending }} Booking</h4>
                    </div>
                    <span class="text-3xl">⏳</span>
                </div>

                <!-- Card: Omzet 7 Hari -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Omzet 7 Hari Terakhir</p>
                        <h4 class="text-3xl font-bold text-green-600 mt-1">
                            Rp {{ number_format($pendapatanMingguan, 0, ',', '.') }}
                        </h4>
                    </div>
                    <span class="text-3xl">💰</span>
                </div>
            </div>

            <!-- Revenue Chart & Summary -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-8">
                <!-- Header Bagian -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Ringkasan Pendapatan GOR</h2>
                        <p class="text-xs text-gray-500">Laporan keuangannya rapi dan langsung kelihatan angkanya</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                        💰 Laporan Keuangan
                    </span>
                </div>

                <!-- Ringkasan Angka Utama -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                        <p class="text-xs font-medium text-indigo-600 uppercase tracking-wider">Pendapatan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-indigo-900 mt-1">
                            Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                        <p class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                        <h3 class="text-2xl font-bold text-emerald-900 mt-1">
                            Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                        <p class="text-xs font-medium text-amber-600 uppercase tracking-wider">Total Semua Transaksi</p>
                        <h3 class="text-2xl font-bold text-amber-900 mt-1">
                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Tabel Rincian Pendapatan Bulanan -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 border border-gray-100 rounded-lg overflow-hidden">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3">Bulan</th>
                                <th class="px-4 py-3">Jumlah Booking</th>
                                <th class="px-4 py-3 text-right">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($rekapBulanan ?? [] as $data)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $data->bulan }}</td>
                                <td class="px-4 py-3">{{ $data->total_booking }} Transaksi</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                    Rp {{ number_format($data->total_uang, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                    Belum ada data pendapatan yang tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Booking Management Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Semua Pesanan Lapangan</h3>
                    <p class="text-xs text-gray-500">Kelola persetujuan sewa dan hubungi pelanggan lewat sini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold">
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tanggal Main</th>
                                <th class="p-3">Jam</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if($allBookings->isEmpty())
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    Belum ada riwayat booking yang masuk.
                                </td>
                            </tr>
                            @else
                            @foreach($allBookings as $b)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="p-3">
                                    <p class="font-bold text-gray-800">{{ $b->nama_pelanggan }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $b->no_whatsapp }}</p>
                                </td>
                                <td class="p-3 text-gray-700">
                                    {{ date('d M Y', strtotime($b->tanggal_main)) }}
                                </td>
                                <td class="p-3 font-medium text-gray-800">
                                    {{ date('H:i', strtotime($b->jam_mulai)) }} - {{ date('H:i', strtotime($b->jam_selesai)) }}
                                </td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                        @if(strtolower($b->status) == 'pending')
                                            bg-amber-100 text-amber-800
                                        @elseif(strtolower($b->status) == 'disetujui')
                                            bg-green-100 text-green-800
                                        @elseif(strtolower($b->status) == 'selesai')
                                            bg-blue-100 text-blue-800
                                        @elseif(strtolower($b->status) == 'dibatalkan')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        {{-- 1. WhatsApp Contact --}}
                                        @php
                                            $noWaClean = preg_replace('/[^0-9]/', '', $b->no_whatsapp);
                                            if(str_starts_with($noWaClean, '0')) {
                                                $noWaClean = '62' . substr($noWaClean, 1);
                                            }
                                            $pesanWA = rawurlencode(
                                                "Halo " . $b->nama_pelanggan .
                                                ", kami dari Admin GOR Raden Ganda ingin mengonfirmasi booking Anda untuk tanggal " .
                                                date('d-m-Y', strtotime($b->tanggal_main)) .
                                                " jam " . date('H:i', strtotime($b->jam_mulai)) . " WIB."
                                            );
                                        @endphp

                                        <a href="https://wa.me/{{ $noWaClean }}?text={{ $pesanWA }}"
                                            target="_blank"
                                            class="px-3 py-1.5 bg-green-600 text-white rounded text-xs font-semibold hover:bg-green-700 transition shadow-sm whitespace-nowrap"
                                            title="Hubungi via WhatsApp">
                                            📱 Chat WA
                                        </a>

                                        {{-- 2. Kondisi Berdasarkan Status --}}
                                        @if(strtolower($b->status) === 'pending')
                                            <form action="{{ route('admin.booking.setujui', $b->id) }}" method="POST" onsubmit="return confirm('Setujui booking ini?')" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded text-xs font-semibold hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                                                    🟢 Setujui
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.booking.batalkan', $b->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-semibold hover:bg-red-600 transition shadow-sm whitespace-nowrap">
                                                    🔴 Batalkan
                                                </button>
                                            </form>

                                        @elseif(strtolower($b->status) === 'disetujui')
                                            <form action="{{ url('/admin/booking/' . $b->id . '/selesai') }}" method="POST" onsubmit="return confirm('Apakah pembayaran sudah diterima dan main selesai?')" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                                                    ✅ Tandai Selesai
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.booking.batalkan', $b->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded text-xs font-semibold hover:bg-red-600 transition shadow-sm whitespace-nowrap">
                                                    🔴 Batalkan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- 3. Tombol Hapus --}}
                                        <form action="{{ route('admin.booking.destroy', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pesanan dari {{ $b->nama_pelanggan }} secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1.5 bg-gray-500 hover:bg-gray-700 text-white font-semibold rounded text-xs transition shadow-sm whitespace-nowrap">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Closed Dates Management -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Set Closed Date Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-md font-bold text-gray-800 mb-2">Set GOR Libur / Tutup</h3>
                    <p class="text-xs text-gray-500 mb-4">Pilih tanggal di mana GOR tidak menerima booking sama sekali.</p>

                    @if(session('warning_booking_exist'))
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg text-xs">
                        <p class="font-bold mb-1">⚠️ Peringatan Deteksi Pesanan!</p>
                        <p>Ada <span class="font-bold text-red-600">{{ session('warning_booking_exist')['jumlah'] }} pesanan aktif</span> pada tanggal {{ session('warning_booking_exist')['tanggal'] }}.</p>
                        <p class="mt-1 text-gray-600">Pastikan Anda telah menghubungi/membatalkan pesanan tersebut sebelum mengunci rute.</p>
                    </div>
                    @endif

                    <form action="{{ route('admin.libur.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alasan Penutupan</label>
                            <input type="text" name="alasan" value="{{ old('alasan') }}" placeholder="Contoh: Turnamen Internal / Renovasi" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                        </div>

                        @if(session('warning_booking_exist'))
                        <div class="p-2.5 bg-gray-50 rounded-lg border border-gray-200 flex items-start gap-2 my-2">
                            <input type="checkbox" name="paksa_tutup" id="paksa_tutup" required class="mt-0.5 rounded text-red-600 focus:ring-red-500">
                            <label for="paksa_tutup" class="text-[11px] text-gray-700 font-medium leading-tight">
                                Ya, saya tahu ada pesanan di tanggal ini. Saya tetap ingin mengunci GOR dan akan mengurus pembatalan manual pelanggan.
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg text-xs shadow transition">
                            🚨 TETAP PAKSA KUNCI TANGGAL
                        </button>
                        <a href="{{ route('dashboard') }}" class="block text-center text-xs font-semibold text-gray-500 hover:text-gray-700 mt-2 hover:underline">
                            Batalkan Penguncian
                        </a>
                        @else
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg text-xs shadow transition">
                            🔒 Kunci Tanggal Libur
                        </button>
                        @endif
                    </form>
                </div>

                <!-- Closed Dates Table -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-md font-bold text-gray-800 mb-1">Agenda GOR Tutup Mendatang</h3>
                    <p class="text-xs text-gray-500 mb-4">Daftar tanggal libur terjadwal yang sedang aktif.</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold">
                                    <th class="p-2.5">Tanggal</th>
                                    <th class="p-2.5">Alasan Keterangan</th>
                                    <th class="p-2.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if($daftarLibur->isEmpty())
                                <tr>
                                    <td colspan="3" class="p-4 text-center text-gray-400">
                                        Belum ada agenda libur mendatang. GOR buka setiap hari!
                                    </td>
                                </tr>
                                @else
                                @foreach($daftarLibur as $libur)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-2.5 font-bold text-red-600">
                                        {{ date('d M Y', strtotime($libur->tanggal)) }}
                                    </td>
                                    <td class="p-2.5 text-gray-600 font-medium">{{ $libur->alasan }}</td>
                                    <td class="p-2.5 text-center">
                                        <form action="{{ route('admin.libur.destroy', $libur->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Buka kembali GOR di tanggal ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded border border-gray-300 transition text-xs">
                                                🔓 Buka GOR
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>