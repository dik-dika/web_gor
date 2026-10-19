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

        // Ambil semua booking yang aktif pada tanggal tersebut:
        // Yaitu status 'disetujui'/'selesai' ATAU status 'pending' yang belum kadaluarsa (masih dalam 30 menit)
        $bookings = Booking::where('tanggal_main', $tanggalTerpilih)
            ->where(function ($q) {
                $q->whereIn('status', ['disetujui', 'selesai'])
                  ->orWhere(function ($sub) {
                      $sub->where('status', 'pending')
                          ->where('created_at', '>=', now()->subMinutes(30));
                  });
            })
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

        // [LANGKAH 2] Pengaman Backend: Cek apakah tanggal ini libur
        $isLibur = ClosedDate::where('tanggal', $request->tanggal_main)->exists();
        if ($isLibur) {
            return back()->withInput()->with('error', 'Maaf, GOR Raden Ganda sedang tidak beroperasi (Tutup) pada tanggal tersebut.');
        }

        // [LANGKAH 3] LOGIKA ANTI-BENTROK + ANTI-ISENG (EXPIRED 30 MENIT)
        // Jadwal dianggap bentrok HANYA JIKA:
        // Statusnya disetujui/selesai ATAU pending tapi masih dalam kurun waktu 30 menit terakhir.
        $jadwalBentrok = Booking::where('tanggal_main', $request->tanggal_main)
            ->where(function ($q) {
                $q->whereIn('status', ['disetujui', 'selesai'])
                  ->orWhere(function ($sub) {
                      $sub->where('status', 'pending')
                          ->where('created_at', '>=', now()->subMinutes(30));
                  });
            })
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($jadwalBentrok) {
            return back()->withInput()->with('error', 'Maaf, lapangan sudah dipesan pada jam tersebut. Silakan pilih jam atau tanggal lain.');
        }

        // Cek apakah nomor WA ini punya pesanan pending yang MASIH AKTIF (< 30 menit)
        $existingPending = Booking::where('no_whatsapp', $request->no_whatsapp)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if ($existingPending) {
            return back()->withInput()->with('error', 'Anda masih memiliki pesanan pending yang belum dikonfirmasi/DP! Silakan selesaikan transaksi sebelumnya terlebih dahulu.');
        }

        // [LANGKAH 4] Simpan ke Database
        Booking::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'tipe_pelanggan' => $request->tipe_pelanggan ?? 'non-member',
            'no_whatsapp'    => $request->no_whatsapp,
            'nomor_lapangan' => 1,
            'tanggal_main'   => $request->tanggal_main,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
            'status'         => 'pending',
            'expired_at'     => now()->addMinutes(30) // Menyimpan jam expired untuk referensi
        ]);

        // [LANGKAH 5] Return sukses
        return redirect()->route('booking.create')->with('success', 'Booking berhasil diajukan! Silakan melakukan transfer DP dan kirim bukti via WA dalam waktu 30 menit.');
    }
}