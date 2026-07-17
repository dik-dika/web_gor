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
    $bookingHariIni = Booking::where('tanggal_main', $hariIni)
        ->where('status', '!=', 'dibatalkan')
        ->count();
    $bookingPending = Booking::where('status', 'pending')->count();

    // 2. Ambil Semua Booking yang Disetujui
    $bookingDisetujui = Booking::whereIn('status', ['disetujui', 'Disetujui', 'APPROVED'])->get();

    // Hitung Total Pendapatan berdasarkan Durasi Jam (Rp 30.000 / jam) atau total_harga
    $totalPendapatan = 0;
    $pendapatanHariIni = 0;
    $pendapatanBulanIni = 0;

    foreach ($bookingDisetujui as $b) {
        // Jika ada kolom total_harga gunakan itu, jika tidak ada hitung dari durasi jam
        if (isset($b->total_harga) && $b->total_harga > 0) {
            $harga = $b->total_harga;
        } else {
            $mulai = Carbon::parse($b->jam_mulai);
            $selesai = Carbon::parse($b->jam_selesai);
            $durasi = max(1, $mulai->diffInHours($selesai));
            $harga = $durasi * 30000;
        }

        // Akumulasi Total Semua
        $totalPendapatan += $harga;

        // Cek jika main Hari Ini
        if (Carbon::parse($b->tanggal_main)->isToday()) {
            $pendapatanHariIni += $harga;
        }

        // Cek jika main Bulan Ini
        if (Carbon::parse($b->tanggal_main)->isCurrentMonth()) {
            $pendapatanBulanIni += $harga;
        }
    }

    // 3. Rekap Bulanan untuk Tabel (Gunakan format aman)
    $rekapBulanan = Booking::whereIn('status', ['disetujui', 'Disetujui', 'APPROVED'])
        ->selectRaw('MONTH(tanggal_main) as bulan_num, MONTHNAME(tanggal_main) as bulan, COUNT(*) as total_booking')
        ->groupBy('bulan_num', 'bulan')
        ->orderBy('bulan_num', 'asc')
        ->get();

    // 4. Data Pendukung Lainnya
    $allBookings = Booking::orderBy('created_at', 'desc')->get();
    $daftarLibur = ClosedDate::where('tanggal', '>=', now()->toDateString())
        ->orderBy('tanggal', 'asc')
        ->get();

    // Variabel cadangan (biar Blade tidak crash kalau masih ada sisa kode grafik)
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
        $booking->update(['status' => 'disetujui']);
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
