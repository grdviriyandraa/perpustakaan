<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_buku')->nullOnDelete();
            $table->string('kode_buku', 30)->unique();
            $table->string('judul');
            $table->string('penulis', 150);
            $table->string('penerbit', 150)->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_rak', 30)->nullable();
            $table->unsignedInteger('stok_total')->default(1);
            $table->unsignedInteger('stok_tersedia')->default(1);
            $table->enum('cover_color', ['cover-a', 'cover-b', 'cover-c', 'cover-d'])->default('cover-a');
            $table->enum('status_buku', ['tersedia', 'dipinjam', 'tidak_aktif'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
