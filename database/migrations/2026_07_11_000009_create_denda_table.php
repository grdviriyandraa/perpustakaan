<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->unique()->constrained('pengembalian')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_hari_terlambat');
            $table->unsignedInteger('tarif_per_hari');
            $table->unsignedInteger('total_denda');
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
