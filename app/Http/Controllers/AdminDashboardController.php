<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use App\Models\ClosedDate;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Statistik Dasar
        $hariIni = Carbon::today()->toDateString();
        $bookingHariIni = Booking::whereDate('tanggal_main', $hariIni)
            ->where('status', '!=', 'dibatalkan')
            ->count();
        $bookingPending = Booking::where('status', 'pending')->count();

        // 2. AMBIL DATA BERDASARKAN STATUS
        $bookingLunas = Booking::whereIn('status', ['selesai', 'lunas'])->get();
        $bookingAktif = Booking::whereIn('status', ['disetujui', 'Disetujui', 'APPROVED', 'selesai', 'lunas'])->get();

        // Inisialisasi variabel statistik keuangan
        $totalPendapatan = 0;
        $pendapatanHariIni = 0;
        $pendapatanBulanIni = 0;

        // Ambil input tanggal manajemen (default ke hari ini jika kosong)
        $tanggalManajemenPilih = $request->input('tanggal_manajemen', date('Y-m-d'));

        // Ambil data booking khusus untuk tanggal yang dipilih pada manajemen
        $bookingsManajemen = Booking::where('tanggal_main', $tanggalManajemenPilih)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // ------------------------------------------------------------------
        // 3. PROSES LAPORAN PENDAPATAN KESELURUHAN (Hanya yang 'selesai' / 'lunas')
        // ------------------------------------------------------------------
        foreach ($bookingLunas as $b) {
            // Pastikan hanya status selesai atau lunas yang uangnya masuk hitungan omzet
            if (!in_array(strtolower($b->status), ['selesai', 'lunas'])) {
                continue;
            }

            if (trim(strtolower($b->tipe_pelanggan)) === 'member') {
                $harga = $b->total_harga ?? 0;
            } else {
                if (!$b->total_harga || $b->total_harga == 0) {
                    $mulai = \Carbon\Carbon::parse($b->jam_mulai);
                    $selesai = \Carbon\Carbon::parse($b->jam_selesai);
                    $durasi = max(1, $mulai->diffInHours($selesai));
                    $harga = $durasi * 30000;
                } else {
                    $harga = $b->total_harga;
                }
            }

            $totalPendapatan += $harga;

            if (\Carbon\Carbon::parse($b->tanggal_main)->isToday()) {
                $pendapatanHariIni += $harga;
            }

            if (\Carbon\Carbon::parse($b->tanggal_main)->isCurrentMonth()) {
                $pendapatanBulanIni += $harga;
            }
        }

        // ------------------------------------------------------------------
        // 4. PROSES FILTER & HISTORY LAPORAN BULANAN (YANG DIPILIH ADMIN)
        // ------------------------------------------------------------------
        $bulanPilih = $request->input('bulan', date('Y-m'));
        [$tahunFilter, $bulanFilter] = explode('-', $bulanPilih);

        // A. Untuk Tabel Manajemen
        $allBookings = Booking::orderBy('created_at', 'desc')->get();

        // B. Untuk History & Rekap Laporan Bulanan (Mencakup data aktif dan soft-deleted)
        $laporanBulanan = Booking::withTrashed()
            ->whereYear('tanggal_main', $tahunFilter)
            ->whereMonth('tanggal_main', $bulanFilter)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal_main', 'asc')
            ->get();

        // Hitung total omzet khusus untuk bulan yang sedang dipilih secara dinamis
        $totalOmzetBulan = 0;
        foreach ($laporanBulanan as $trx) {
            // Uang hanya masuk jika statusnya benar-benar sudah 'selesai' atau 'lunas'
            if (!in_array(strtolower($trx->status), ['selesai', 'lunas'])) {
                continue;
            }

            $tipeTrx = strtolower(trim($trx->tipe_pelanggan));

            if ($tipeTrx === 'member') {
                // Ambil total_harga member, jika kosong/0 anggap 50000 sebagai standar minimal
                $subTotal = ($trx->total_harga > 0) ? $trx->total_harga : 50000;
            } else {
                if (!$trx->total_harga || $trx->total_harga == 0) {
                    $mulai = Carbon::parse($trx->jam_mulai);
                    $selesai = Carbon::parse($trx->jam_selesai);
                    $durasi = max(1, $mulai->diffInHours($selesai));
                    $subTotal = $durasi * 30000; 
                } else {
                    $subTotal = $trx->total_harga;
                }
            }

            $totalOmzetBulan += $subTotal;
        }

        // ------------------------------------------------------------------
        // 5. PROSES REKAP BULANAN (UNTUK KARTU / LIST REKAP KESELURUHAN)
        // ------------------------------------------------------------------
        $rekapBulananRaw = [];

        foreach ($bookingAktif as $b) {
            $bulanKey = Carbon::parse($b->tanggal_main)->translatedFormat('F Y');
            $sortKey  = Carbon::parse($b->tanggal_main)->format('Ym');

            $hargaRekap = $b->total_harga ?? 0;

            if (!isset($rekapBulananRaw[$bulanKey])) {
                $rekapBulananRaw[$bulanKey] = [
                    'bulan' => $bulanKey,
                    'total_booking' => 0,
                    'total_uang' => 0,
                    'sort_key' => $sortKey
                ];
            }

            $rekapBulananRaw[$bulanKey]['total_booking'] += 1;

            if (in_array(strtolower($b->status), ['selesai', 'lunas'])) {
                $rekapBulananRaw[$bulanKey]['total_uang'] += $hargaRekap;
            }
        }

        $rekapBulanan = collect($rekapBulananRaw)->map(function ($item) {
            return (object) $item;
        })->sortByDesc('sort_key');

        // 6. Data Pendukung Lainnya
        $daftarLibur = ClosedDate::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        $laporanMingguan = [];
        $pendapatanMingguan = $pendapatanBulanIni;
        $maxOmzetHarian = [];

        return view('dashboard', compact(
            'bookingHariIni',
            'bookingPending',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'totalPendapatan',
            'rekapBulanan',
            'laporanBulanan',
            'totalOmzetBulan',
            'bulanPilih',
            'allBookings',
            'daftarLibur',
            'laporanMingguan',
            'pendapatanMingguan',
            'maxOmzetHarian',
            'tanggalManajemenPilih',
            'bookingsManajemen'
        ));
    }

    // Aksi untuk Menyetujui Booking (Jadwal Terkunci)
    public function setujui($id)
    {
        $booking = Booking::findOrFail($id);

        if (trim(strtolower($booking->tipe_pelanggan)) === 'member') {
            $hargaFinal = $booking->total_harga ?? 0;
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

    // Normalisasi teks tipe pelanggan untuk menghindari masalah huruf kapital/spasi
    $tipe = strtolower(trim($booking->tipe_pelanggan));

    if ($tipe === 'member') {
        // Jika member, tentukan harganya secara pasti berdasarkan total_harga yang ada, 
        // atau fallback otomatis ke 50000 jika sebelumnya 0/null
        $hargaFinal = ($booking->total_harga > 0) ? $booking->total_harga : 50000;
    } else {
        // Hitung untuk non-member seperti biasa
        if (!$booking->total_harga || $booking->total_harga == 0) {
            $mulai = Carbon::parse($booking->jam_mulai);
            $selesai = Carbon::parse($booking->jam_selesai);
            $durasi = max(1, $mulai->diffInHours($selesai));
            $hargaFinal = $durasi * 30000;
        } else {
            $hargaFinal = $booking->total_harga;
        }
    }

    $booking->update([
        'status' => 'selesai',
        'total_harga' => $hargaFinal
    ]);

    return redirect()->back()->with('success', 'Booking berhasil ditandai selesai dan uang masuk ke laporan!');
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
