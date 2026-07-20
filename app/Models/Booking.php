<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

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
}