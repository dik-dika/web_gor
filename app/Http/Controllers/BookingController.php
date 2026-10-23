<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ClosedDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 1. Menampilkan Form Booking
    public function create(Request $request)
    {
        $tanggalTerpilih = $request->get('tanggal_filter', date('Y-m-d'));

        $infoLibur = ClosedDate::where('tanggal', $tanggalTerpilih)->first();

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
            'jam_selesai'    => 'required',
        ]);

        // [LANGKAH 2] Cek apakah tanggal libur
        $isLibur = ClosedDate::where('tanggal', $request->tanggal_main)->exists();
        if ($isLibur) {
            return back()->withInput()->with('error', 'Maaf, GOR Raden Ganda sedang tidak beroperasi (Tutup) pada tanggal tersebut.');
        }

        // [LANGKAH 3] Penyesuaian Durasi & Jam Selesai
        try {
            $mulai = Carbon::parse($request->jam_mulai);

            if (trim(strtolower($request->tipe_pelanggan ?? '')) === 'member') {
                $durasiPaket = ($request->total_harga == 50000) ? 3 : 5;
                $jamSelesaiSeharusnya = $mulai->copy()->addHours($durasiPaket)->format('H:i');

                $request->merge(['jam_selesai' => $jamSelesaiSeharusnya]);
                $selesai = Carbon::parse($jamSelesaiSeharusnya);
            } else {
                $selesai = Carbon::parse($request->jam_selesai);
                $durasiPaket = $mulai->diffInHours($selesai);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Format jam mulai atau jam selesai tidak valid.');
        }

        if ($durasiPaket < 1) {
            return back()->withInput()->with('error', 'Jam selesai harus lebih besar dari jam mulai minimal 1 jam.');
        }

        $inputJamMulai = $mulai->format('H:i:s');
        $inputJamSelesai = $selesai->format('H:i:s');

        // [LANGKAH 4] LOGIKA ANTI-BENTROK YANG PRESISI
        $jadwalBentrok = Booking::where('tanggal_main', $request->tanggal_main)
            ->where(function ($q) {
                $q->whereIn('status', ['disetujui', 'selesai'])
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'pending')
                            ->where('created_at', '>=', now()->subMinutes(30));
                    });
            })
            ->where(function ($query) use ($inputJamMulai, $inputJamSelesai) {
                $query->where('jam_mulai', '<', $inputJamSelesai)
                    ->where('jam_selesai', '>', $inputJamMulai);
            })
            ->exists();

        if ($jadwalBentrok) {
            return back()->withInput()->with('error', 'Maaf, lapangan sudah dipesan pada jam tersebut. Silakan pilih jam atau tanggal lain.');
        }

        // Cek apakah nomor WA ini punya pesanan pending aktif (< 30 menit)
        $existingPending = Booking::where('no_whatsapp', $request->no_whatsapp)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if ($existingPending) {
            return back()->withInput()->with('error', 'Anda masih memiliki pesanan pending yang belum dikonfirmasi/DP! Silakan selesaikan transaksi sebelumnya terlebih dahulu.');
        }

        // [LANGKAH 5] Simpan ke Database
        $finalHarga = 0;
        if (trim(strtolower($request->tipe_pelanggan ?? '')) === 'member') {
            // Ambil dari request, jika kosong fallback ke 50000 (atau sesuaikan paket)
            $finalHarga = $request->total_harga ? intval($request->total_harga) : 50000;
        } else {
            // Untuk non-member, hitung berdasarkan durasi jam (misal Rp 30.000 / jam)
            $durasiJam = $mulai->diffInHours($selesai);
            $finalHarga = $durasiJam * 30000;
        }
        Booking::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'tipe_pelanggan' => $request->tipe_pelanggan ?? 'reguler',
            'no_whatsapp'    => $request->no_whatsapp,
            'tanggal_main'   => $request->tanggal_main,
            'jam_mulai'      => $mulai->format('H:i'),
            'jam_selesai'    => $selesai->format('H:i'),
            'total_harga'    => $finalHarga,
            'status'         => 'pending'
        ]);

        return back()->with('success', 'Pengajuan jadwal sewa berhasil dikirim!');
    }
}
