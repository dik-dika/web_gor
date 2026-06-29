<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController as BreezeProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. RUTE WEB PROFILE (Menggunakan direktori View agar bebas bentrok)
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/fasilitas', function () { return view('fasilitas'); })->name('fasilitas');
Route::get('/harga', function () { return view('harga'); })->name('harga');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

/*
|--------------------------------------------------------------------------
| 2. RUTE BOOKING MANDIRI (Sisi Pelanggan)
|--------------------------------------------------------------------------
*/
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| 3. RUTE DASHBOARD PEMILIK (Diproteksi Auth Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman Utama Dasbor GOR
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Aksi Persetujuan Booking
    Route::post('/admin/booking/{id}/setujui', [AdminDashboardController::class, 'setujui'])->name('admin.booking.setujui');
    Route::post('/admin/booking/{id}/batalkan', [AdminDashboardController::class, 'batalkan'])->name('admin.booking.batalkan');

    // Rute Edit Akun Admin Bawaan Breeze (Wajib ada agar navbar admin tidak error)
    Route::get('/profile', [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [BreezeProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/admin/libur', [AdminDashboardController::class, 'storeLibur'])->name('admin.libur.store');
    Route::delete('/admin/libur/{id}', [AdminDashboardController::class, 'destroyLibur'])->name('admin.libur.destroy');
});

/*
|--------------------------------------------------------------------------
| 4. RUTE OTOMATIS LOGIN & REGISTER BREEZE
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // ... rute-rute admin yang sudah ada ...

    // Rute Baru untuk Hapus Pesanan Lapangan
    Route::delete('/admin/booking/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.booking.destroy');
});