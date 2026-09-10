<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use App\Models\ClosedDate;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik Dasar
        $hariIni = Carbon::today()->toDateString();
        $bookingHariIni = Booking::whereDate('tanggal_main', $hariIni)
            ->where('status', '!=', 'dibatalkan')
            ->count();
        $bookingPending = Booking::where('status', 'pending')->count();

        // 2. AMBIL DATA BERDASARKAN STATUS
        // A. Booking Lunas / Selesai -> Untuk Keuangan
        $bookingLunas = Booking::whereIn('status', ['selesai', 'lunas'])->get();

        // B. Booking Aktif -> Untuk Rekap Jumlah Transaksi Bulanan
        $bookingAktif = Booking::whereIn('status', ['disetujui', 'Disetujui', 'APPROVED', 'selesai', 'lunas'])->get();

        // Inisialisasi variabel statistik keuangan
        $totalPendapatan = 0;
        $pendapatanHariIni = 0;
        $pendapatanBulanIni = 0;

        // ------------------------------------------------------------------
        // 3. PROSES LAPORAN PENDAPATAN (HANYA DARI BOOKING YANG SUDAH LUNAS / SELESAI)
        // ------------------------------------------------------------------
        foreach ($bookingLunas as $b) {
            // Jika member, nominal omzet tetap 0
            if (trim(strtolower($b->tipe_pelanggan)) === 'member') {
                $harga = 0;
            } else {
                // 2. Jika total_harga kosong atau 0, hitung otomatis berdasarkan jam
                if (!$b->total_harga || $b->total_harga == 0) {
                    $mulai = \Carbon\Carbon::parse($b->jam_mulai);
                    $selesai = \Carbon\Carbon::parse($b->jam_selesai);
                    $durasi = max(1, $mulai->diffInHours($selesai));
                    $harga = $durasi * 30000;
                } else {
                    $harga = $b->total_harga;
                }
            }

            // Akumulasi Total Uang Masuk
            $totalPendapatan += $harga;

            if (\Carbon\Carbon::parse($b->tanggal_main)->isToday()) {
                $pendapatanHariIni += $harga;
            }

            if (\Carbon\Carbon::parse($b->tanggal_main)->isCurrentMonth()) {
                $pendapatanBulanIni += $harga;
            }
        }

        // ------------------------------------------------------------------
        // 4. PROSES REKAP BULANAN (REKAP JUMLAH TRANSAKSI & OMZET BULANAN)
        // ------------------------------------------------------------------
        $rekapBulananRaw = [];

        foreach ($bookingAktif as $b) {
            // Gunakan format "Bulan Tahun" (Contoh: "July 2026") agar rapi saat berganti tahun
            $bulanKey = Carbon::parse($b->tanggal_main)->translatedFormat('F Y');
            $sortKey  = Carbon::parse($b->tanggal_main)->format('Ym'); // Urutan tahun + bulan (misal: 202607)

            $hargaRekap = (strtolower($b->tipe_pelanggan) === 'member') ? 0 : ($b->total_harga ?? 0);

            if (!isset($rekapBulananRaw[$bulanKey])) {
                $rekapBulananRaw[$bulanKey] = [
                    'bulan' => $bulanKey,
                    'total_booking' => 0,
                    'total_uang' => 0,
                    'sort_key' => $sortKey
                ];
            }

            $rekapBulananRaw[$bulanKey]['total_booking'] += 1;

            // Hanya tambahkan uang ke rekap bulanan jika statusnya sudah selesai/lunas
            if (in_array(strtolower($b->status), ['selesai', 'lunas'])) {
                $rekapBulananRaw[$bulanKey]['total_uang'] += $hargaRekap;
            }
        }

        // Ubah format ke Collection Object & urutkan dari bulan terbaru ke lama
        $rekapBulanan = collect($rekapBulananRaw)->map(function ($item) {
            return (object) $item;
        })->sortByDesc('sort_key');

        // 5. Data Pendukung Lainnya
        $allBookings = Booking::orderBy('created_at', 'desc')->get();
        $daftarLibur = ClosedDate::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        // Variabel cadangan
        $laporanMingguan = [];
        $pendapatanMingguan = $pendapatanBulanIni;
        $maxOmzetHarian = 30000;

        return view('dashboard', compact(
            'bookingHariIni',
            'bookingPending',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'totalPendapatan',
            'rekapBulanan',
            'allBookings',
            'daftarLibur',
            'laporanMingguan',
            'pendapatanMingguan',
            'maxOmzetHarian'
        ));
    }

    // Aksi untuk Menyetujui Booking (Jadwal Terkunci)
    public function setujui($id)
    {
        $booking = Booking::findOrFail($id);

        if (strtolower($booking->tipe_pelanggan) === 'member') {
            $hargaFinal = 0;
        } else {
            $mulai = Carbon::parse($booking->jam_mulai);
            $selesai = Carbon::parse($booking->jam_selesai);
            $durasi = max(1, $mulai->diffInHours($selesai));
            $hargaFinal = $durasi * 30000;
        }

        $booking->update([
            'status' => 'disetujui',
            'total_harga' => $hargaFinal
        ]);

        return back()->with('success', 'Booking berhasil disetujui! Jadwal telah terkunci.');
    }

    // Aksi untuk Pelunasan / Selesai Bermain (Uang Masuk Kas)
    public function selesai($id)
    {
        $booking = Booking::findOrFail($id);

        // Perhitungan ulang harga jika total_harga di database masih 0/null saat ditandai selesai
        if (strtolower($booking->tipe_pelanggan) === 'member') {
            $hargaFinal = 0;
        } else {
            if (!$booking->total_harga || $booking->total_harga == 0) {
                $mulai = Carbon::parse($booking->jam_mulai);
                $selesai = Carbon::parse($booking->jam_selesai);
                $durasi = max(1, $mulai->diffInHours($selesai));
                $hargaFinal = $durasi * 30000;
            } else {
                $hargaFinal = $booking->total_harga;
            }
        }

        // Update status dan simpan total harga secara pasti
        $booking->update([
            'status' => 'selesai',
            'total_harga' => $hargaFinal
        ]);

        return redirect()->back()->with('success', 'Booking berhasil ditandai selesai dan masuk ke laporan!');
    }

    // Aksi untuk Membatalkan Booking
    public function batalkan($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'dibatalkan']);
        return back()->with('success', 'Booking berhasil dibatalkan!');
    }

    // Aksi untuk Menyimpan Tanggal Libur
    public function storeLibur(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today|unique:closed_dates,tanggal',
            'alasan' => 'required|string|max:255',
        ]);

        $jumlahBookingBentrok = Booking::where('tanggal_main', $request->tanggal)
            ->where('status', '!=', 'dibatalkan')
            ->count();

        if ($jumlahBookingBentrok > 0 && !$request->has('paksa_tutup')) {
            return back()
                ->withInput()
                ->with('warning_booking_exist', [
                    'jumlah' => $jumlahBookingBentrok,
                    'tanggal' => date('d M Y', strtotime($request->tanggal))
                ]);
        }

        ClosedDate::create([
            'tanggal' => $request->tanggal,
            'alasan' => $request->alasan,
        ]);

        return back()->with('success', 'Tanggal libur berhasil ditambahkan!');
    }

    public function destroyLibur($id)
    {
        $libur = ClosedDate::findOrFail($id);
        $libur->delete();

        return back()->with('success', 'Tanggal libur berhasil dihapus, GOR dibuka kembali!');
    }

    // Fungsi untuk Menghapus Data Pesanan Lapangan
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Data pesanan berhasil dihapus permanen!');
    }
}
