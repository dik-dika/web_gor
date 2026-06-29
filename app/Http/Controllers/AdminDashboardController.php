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
        // 1. Hitung Statistik
        $hariIni = Carbon::today()->toDateString();
        $bookingHariIni = Booking::where('tanggal_main', $hariIni)
            ->where('status', '!=', 'dibatalkan')
            ->count();
        $bookingPending = Booking::where('status', 'pending')->count();

        // 2. Siapkan wadah laporan mingguan (7 hari terakhir)
        $laporanMingguan = [];
        $kamusHari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $tanggalObj = Carbon::today()->subDays($i);
            $formatTanggal = $tanggalObj->toDateString();
            $namaHariIndo = $kamusHari[$tanggalObj->format('l')];

            $laporanMingguan[$formatTanggal] = [
                'hari' => $namaHariIndo . ' (' . $tanggalObj->format('d/m') . ')',
                'omzet' => 0
            ];
        }

        // 3. Hitung pendapatan mingguan (7 hari terakhir, status disetujui)
        $tujuhHariLalu = Carbon::today()->subDays(7)->toDateString();
        $bookingDisetujuiMingguIni = Booking::where('status', 'disetujui')
            ->whereBetween('tanggal_main', [$tujuhHariLalu, $hariIni])
            ->get();

        $pendapatanMingguan = 0;
        $maxOmzetHarian = 30000;

        foreach ($bookingDisetujuiMingguIni as $b) {
            // Hitung durasi jam bermain
            $mulai = Carbon::parse($b->jam_mulai);
            $selesai = Carbon::parse($b->jam_selesai);
            $durasiJam = $mulai->diffInHours($selesai);
            $totalBayar = $durasiJam * 30000;

            // Akumulasi pendapatan
            $pendapatanMingguan += $totalBayar;

            // Masukkan ke laporan harian yang sesuai
            if (isset($laporanMingguan[$b->tanggal_main])) {
                $laporanMingguan[$b->tanggal_main]['omzet'] += $totalBayar;

                // Update omzet tertinggi untuk skala grafik
                if ($laporanMingguan[$b->tanggal_main]['omzet'] > $maxOmzetHarian) {
                    $maxOmzetHarian = $laporanMingguan[$b->tanggal_main]['omzet'];
                }
            }
        }

        // 4. Ambil semua data booking (urutan terbaru di atas)
        $allBookings = Booking::orderBy('created_at', 'desc')->get();

        // 5. Ambil data tanggal libur mendatang
        $daftarLibur = ClosedDate::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        // Return view dengan semua data
        return view('dashboard', compact(
            'bookingHariIni',
            'bookingPending',
            'pendapatanMingguan',
            'laporanMingguan',
            'maxOmzetHarian',
            'allBookings',
            'daftarLibur',
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
