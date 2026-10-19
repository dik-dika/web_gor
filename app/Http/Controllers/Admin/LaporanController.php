<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: Tanggal awal & akhir bulan ini
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-t'));

        // Query Ambil booking yang statusnya 'selesai' atau 'disetujui' dalam rentang tanggal
        $query = Booking::whereBetween('tanggal_main', [$tanggalMulai, $tanggalSelesai])
            ->whereIn('status', ['selesai', 'disetujui']);

        if ($request->has('tipe_pelanggan') && $request->tipe_pelanggan != '') {
            $query->where('tipe_pelanggan', $request->tipe_pelanggan);
        }

        $laporanBookings = $query->orderBy('tanggal_main', 'asc')->get();

        $totalPendapatan = $laporanBookings->sum(function($b) {
            return $b->total_harga ?? 50000;
        });

        $totalTransaksi = $laporanBookings->count();

        return view('admin.laporan.index', compact(
            'laporanBookings', 
            'tanggalMulai', 
            'tanggalSelesai', 
            'totalPendapatan', 
            'totalTransaksi'
        ));
    }
}