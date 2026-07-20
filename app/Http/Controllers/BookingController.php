<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ClosedDate;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // 1. Menampilkan Form Booking
    public function create(Request $request)
    {
        // Ambil tanggal dari filter, jika tidak ada default ke hari ini
        $tanggalTerpilih = $request->get('tanggal_filter', date('Y-m-d'));

        // Cek apakah tanggal ini terdaftar sebagai hari libur
        $infoLibur = ClosedDate::where('tanggal', $tanggalTerpilih)->first();

        // Ambil semua booking yang aktif (tidak dibatalkan) pada tanggal tersebut
        $bookings = Booking::where('tanggal_main', $tanggalTerpilih)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('booking', compact('bookings', 'tanggalTerpilih', 'infoLibur'));
    }

    // 2. Memproses Data Booking dari Pelanggan
    public function store(Request $request)
    {
        // [LANGKAH 1] Validasi input awal
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_whatsapp'    => 'required|string|max:20',
            'tanggal_main'   => 'required|date|after_or_equal:today',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        // [LANGKAH 2] Pengaman Backend: Cek apakah tanggal ini libur (WAJIB DI ATAS)
        $isLibur = ClosedDate::where('tanggal', $request->tanggal_main)->exists();
        if ($isLibur) {
            return back()->withInput()->with('error', 'Maaf, GOR Raden Ganda sedang tidak beroperasi (Tutup) pada tanggal tersebut.');
        }

        // [LANGKAH 3] LOGIKA ANTI-BENTROK: Cek apakah jam tersebut sudah di-booking orang lain
        $jadwalBentrok = Booking::where('tanggal_main', $request->tanggal_main)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($jadwalBentrok) {
            return back()->withInput()->with('error', 'Maaf, lapangan sudah dipesan pada jam tersebut. Silakan pilih jam atau tanggal lain.');
        }

        // [LANGKAH 4] Jika lolos cek libur & bentrok, baru simpan ke database
        Booking::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'tipe_pelanggan' => $request->tipe_pelanggan ?? 'non-member', // Default ke non-member jika tidak diisi
            'no_whatsapp'    => $request->no_whatsapp,
            'nomor_lapangan' => 1,
            'tanggal_main'   => $request->tanggal_main,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
            'status'         => 'pending' // Menunggu verifikasi pemilik
        ]);
        
        // [LANGKAH 5] Return sukses diletakkan di paling bawah luar IF
        return redirect()->route('booking.create')->with('success', 'Booking berhasil diajukan! Silakan tunggu konfirmasi admin.');
    }
}