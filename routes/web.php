<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController as BreezeProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. RUTE WEB PROFILE (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/fasilitas', function () {
    return view('fasilitas');
})->name('fasilitas');

Route::get('/harga', function () {
    return view('harga');
})->name('harga');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

/*
|--------------------------------------------------------------------------
| 2. RUTE BOOKING MANDIRI (Sisi Pelanggan)
|--------------------------------------------------------------------------
*/
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| 3. RUTE DASHBOARD & ADMIN (Diproteksi Auth & Verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Grouping Khusus Fitur Admin (/admin/...)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Status & Aksi Booking
        Route::post('/booking/{id}/setujui', [AdminDashboardController::class, 'setujui'])->name('booking.setujui');
        Route::post('/booking/{id}/batalkan', [AdminDashboardController::class, 'batalkan'])->name('booking.batalkan');
        Route::post('/booking/{id}/selesai', [AdminDashboardController::class, 'selesai'])->name('booking.selesai');
        Route::delete('/booking/{id}', [AdminDashboardController::class, 'destroy'])->name('booking.destroy');

        // Manajemen Tanggal Libur GOR
        Route::post('/libur', [AdminDashboardController::class, 'storeLibur'])->name('libur.store');
        Route::delete('/libur/{id}', [AdminDashboardController::class, 'destroyLibur'])->name('libur.destroy');

        // Laporan Pendapatan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Profile Admin/User (Pastikan tidak duplikat dengan auth.php)
    Route::get('/profile', [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| 4. RUTE OTOMATIS AUTH BREEZE
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';