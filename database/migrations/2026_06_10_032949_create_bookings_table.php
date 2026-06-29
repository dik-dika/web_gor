<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_whatsapp');
            $table->integer('nomor_lapangan'); // Contoh: Lapangan 1, 2, atau 3
            $table->date('tanggal_main');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            // Status untuk dasbor pemilik nanti (pending, disetujui, dibatalkan)
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
