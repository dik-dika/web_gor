@extends('layouts.front')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-6xl">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-5 bg-white rounded-xl shadow-md p-6 border border-gray-100 h-fit">
            <h2 class="text-xl font-bold text-indigo-900 mb-6">Form Booking Lapangan</h2>

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm font-medium">
                {{ session('error') }}
            </div>
            @endif

            @if($infoLibur)
            <div class="bg-red-50 border-2 border-dashed border-red-200 text-red-900 p-6 rounded-xl text-center my-6">
                <span class="text-4xl block mb-2">⚠️</span>
                <h4 class="font-bold text-base">MOHON MAAF, GOR TUTUP</h4>
                <p class="text-xs text-red-600 mt-1 font-medium bg-red-100/50 py-1.5 px-3 rounded-lg inline-block">
                    Alasan: {{ $infoLibur->alasan }}
                </p>
                <p class="text-xs text-gray-500 mt-3">Silakan ganti filter "Pilih Tanggal" di sebelah kanan untuk mencari hari operasional lainnya.</p>
            </div>
            @else

            <form action="{{ route('booking.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" placeholder="Contoh: 0812345678" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Main</label>
                    <input type="date" name="tanggal_main" value="{{ old('tanggal_main', $tanggalTerpilih) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-900 hover:bg-indigo-950 text-white font-bold py-3 rounded-lg shadow-md transition tracking-wide mt-4 text-sm">
                    Ajukan Jadwal Sewa
                </button>
            </form>
            @endif
        </div>

        <div class="lg:col-span-7 bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-indigo-900">Status Ketersediaan Lapangan</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Melihat jadwal yang sudah dipesan</p>
                </div>

                <form action="{{ route('booking.create') }}" method="GET" class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-gray-600 whitespace-nowrap">Pilih Tanggal:</label>
                    <input type="date" name="tanggal_filter" value="{{ $tanggalTerpilih }}" onchange="this.form.submit()"
                        class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none font-medium text-gray-700">
                </form>
            </div>

            <div class="space-y-3">
                <div class="bg-indigo-50 text-indigo-900 px-4 py-2.5 rounded-lg text-sm font-semibold flex justify-between items-center">
                    <span>📅 Tanggal: {{ \Carbon\Carbon::parse($tanggalTerpilih)->translatedFormat('d F Y') }}</span>
                    <span class="text-xs bg-indigo-200 px-2 py-0.5 rounded text-indigo-800">1 Lapangan</span>
                </div>

                @if($bookings->isEmpty())
                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
                    <span class="text-4xl block mb-2">🟢</span>
                    <p class="text-sm font-medium text-gray-600">Lapangan Kosong Seharian!</p>
                    <p class="text-xs text-gray-400 mt-1">Belum ada yang booking pada tanggal ini. Silakan pilih jam sesukamu.</p>
                </div>
                @else
                <div class="grid grid-cols-1 gap-2">
                    @foreach($bookings as $book)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-red-100 bg-red-50/40">
                        <div class="flex items-center gap-4">
                            <div class="bg-red-500 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg tracking-wider">
                                TERISI
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ date('H:i', strtotime($book->jam_mulai)) }} - {{ date('H:i', strtotime($book->jam_selesai)) }} WIB
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">Penyewa: {{ $book->nama_pelanggan }}</p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-800 font-medium capitalized">
                            {{ $book->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="mt-6 text-xs text-gray-400 bg-gray-50 p-3 rounded-lg leading-relaxed">
                * Jadwal di atas diperbarui secara *real-time*. Jika status masih <span class="text-amber-600 font-semibold">pending</span>, jam tersebut sudah di-tag namun menunggu transfer pembayaran dikonfirmasi oleh pemilik GOR.
            </div>
        </div>

    </div>
</div>
@endsection