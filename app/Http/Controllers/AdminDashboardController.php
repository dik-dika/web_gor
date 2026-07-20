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
        // Gunakan whereDate untuk memastikan perbandingan tanggal tepat
        $bookingHariIni = Booking::whereDate('tanggal_main', $hariIni)
            ->where('status', '!=', 'dibatalkan')
            ->count();
        $bookingPending = Booking::where('status', 'pending')->count();

        // 2. Ambil Semua Booking yang Disetujui
        $bookingDisetujui = Booking::whereIn('status', ['disetujui', 'Disetujui', 'APPROVED'])->get();

        // Inisialisasi variabel statistik
        $totalPendapatan = 0;
        $pendapatanHariIni = 0;
        $pendapatanBulanIni = 0;

        // Wadah untuk menampung olahan rekap bulanan secara akurat
        $rekapBulananRaw = [];

        foreach ($bookingDisetujui as $b) {
            // Gunakan strtolower untuk mengantisipasi tulisan 'Member' atau 'MEMBER'
            if (strtolower($b->tipe_pelanggan) === 'member') {
                $harga = 0;
            } else {
                // Jika non-member, hitung normal seperti biasa
                if (isset($b->total_harga) && $b->total_harga > 0) {
                    $harga = $b->total_harga;
                } else {
                    $mulai = Carbon::parse($b->jam_mulai);
                    $selesai = Carbon::parse($b->jam_selesai);
                    $durasi = max(1, $mulai->diffInHours($selesai));
                    $harga = $durasi * 30000;
                }
            }

            // Akumulasi Total Stat Cards
            $totalPendapatan += $harga;

            if (Carbon::parse($b->tanggal_main)->isToday()) {
                $pendapatanHariIni += $harga;
            }

            if (Carbon::parse($b->tanggal_main)->isCurrentMonth()) {
                $pendapatanBulanIni += $harga;
            }

            // PROSES REKAP BULANAN (SINKRON DENGAN LOGIKA MEMBER)
            $bulanKey = Carbon::parse($b->tanggal_main)->format('F'); // Contoh: "July"
            $bulanNum = Carbon::parse($b->tanggal_main)->format('m');

            if (!isset($rekapBulananRaw[$bulanKey])) {
                $rekapBulananRaw[$bulanKey] = [
                    'bulan' => $bulanKey,
                    'total_booking' => 0,
                    'total_uang' => 0,
                    'bulan_num' => $bulanNum
                ];
            }

            $rekapBulananRaw[$bulanKey]['total_booking'] += 1;
            $rekapBulananRaw[$bulanKey]['total_uang'] += $harga; // Menambahkan nominal asli (0 jika member)
        }

        // Ubah format array menjadi collection object agar Blade tidak eror saat looping
        $rekapBulanan = collect($rekapBulananRaw)->map(function ($item) {
            return (object) $item;
        })->sortBy('bulan_num');

        // 4. Data Pendukung Lainnya
        $allBookings = Booking::orderBy('created_at', 'desc')->get();
        $daftarLibur = ClosedDate::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        // Variabel cadangan agar template grafis tidak crash
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

    // Aksi untuk Menyetujui Booking
    public function setujui($id)
    {
        $booking = Booking::findOrFail($id);

        // Antisipasi perbedaan kapitalisasi huruf
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

        return back()->with('success', 'Booking berhasil disetujui!');
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

        // 🔍 LANGKAH PENGAMAN: Cek apakah ada booking aktif (belum dibatalkan) di tanggal tersebut
        $jumlahBookingBentrok = Booking::where('tanggal_main', $request->tanggal)
            ->where('status', '!=', 'dibatalkan')
            ->count();

        // Jika ada booking DAN pemilik belum mencentang konfirmasi paksa
        if ($jumlahBookingBentrok > 0 && !$request->has('paksa_tutup')) {
            return back()
                ->withInput() // Mempertahankan isi tanggal & alasan yang tadi diketik
                ->with('warning_booking_exist', [
                    'jumlah' => $jumlahBookingBentrok,
                    'tanggal' => date('d M Y', strtotime($request->tanggal))
                ]);
        }

        // Jika tidak ada booking ATAU pemilik sudah setuju mencentang "paksa_tutup"
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
