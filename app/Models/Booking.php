<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import trait

class Booking extends Model
{
    use HasFactory, SoftDeletes; // 2. Gunakan trait SoftDeletes

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_pelanggan',
        'tipe_pelanggan',
        'no_whatsapp',
        'nomor_lapangan',
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];
    protected $table = 'bookings';
    protected $guarded = ['id'];
}