<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_semua_halaman_publik_terrender(): void
    {
        $this->get('/')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/bantuan')->assertOk();
        $this->get('/admin/login')->assertOk();
    }

    public function test_semua_halaman_anggota_terrender(): void
    {
        $anggota = Anggota::where('username', 'ryan')->first();
        $buku = Buku::first();

        $halaman = [
            '/anggota/dashboard',
            '/anggota/katalog',
            '/anggota/katalog?q=laravel&kategori=1&status=tersedia',
            "/anggota/katalog/{$buku->id}",
            "/anggota/peminjaman/ajukan/{$buku->id}",
            '/anggota/peminjaman/status',
            '/anggota/peminjaman/riwayat',
            '/anggota/denda',
            '/anggota/profil',
            '/anggota/notifikasi',
            '/anggota/bantuan',
        ];

        foreach ($halaman as $url) {
            $this->actingAs($anggota, 'anggota')->get($url)->assertOk();
        }
    }

    public function test_semua_halaman_admin_terrender(): void
    {
        $admin = Admin::first();
        $anggota = Anggota::where('username', 'ryan')->first();
        $buku = Buku::first();

        // Siapkan satu peminjaman berstatus dipinjam untuk halaman pengembalian.
        $this->actingAs($anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $buku->id]);

        $peminjaman = Peminjaman::first();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.peminjaman.validasi', $peminjaman), ['action' => 'setujui']);
        $this->actingAs($admin, 'admin')
            ->patch(route('admin.peminjaman.konfirmasi', $peminjaman));

        $halaman = [
            '/admin/dashboard',
            '/admin/buku',
            '/admin/buku/create',
            "/admin/buku/{$buku->id}/edit",
            '/admin/kategori',
            '/admin/kategori?edit=1',
            '/admin/anggota',
            "/admin/anggota/{$anggota->id}",
            '/admin/peminjaman',
            '/admin/peminjaman?tab=aktif',
            '/admin/peminjaman?tab=selesai',
            "/admin/peminjaman/{$peminjaman->id}",
            '/admin/pengembalian',
            "/admin/pengembalian/{$peminjaman->id}/catat",
            '/admin/denda',
            '/admin/laporan',
            '/admin/kartu',
            "/admin/kartu?anggota={$anggota->id}&q=ryan",
            '/admin/setting',
            '/admin/notifikasi',
            '/admin/bantuan',
        ];

        foreach ($halaman as $url) {
            $this->actingAs($admin, 'admin')->get($url)->assertOk();
        }
    }

    public function test_export_pdf_laporan_dan_kartu(): void
    {
        $admin = Admin::first();
        $anggota = Anggota::where('username', 'ryan')->first();

        $this->actingAs($admin, 'admin')
            ->get('/admin/laporan/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin, 'admin')
            ->get("/admin/kartu/{$anggota->id}")
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
