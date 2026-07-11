<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Database\Seeders\SystemSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKelolaTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemSettingSeeder::class);

        $this->admin = Admin::create([
            'nama_admin' => 'Admin Perpus',
            'username' => 'admin',
            'email' => 'admin@perpusapp.id',
            'password' => 'admin123',
        ]);
    }

    public function test_admin_bisa_membuat_dan_mengubah_buku(): void
    {
        $kategori = KategoriBuku::create(['nama_kategori' => 'Basis Data']);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.buku.store'), [
                'kode_buku' => 'BK-100',
                'judul' => 'Pemodelan Data',
                'penulis' => 'N. Putri',
                'kategori_id' => $kategori->id,
                'stok_total' => 3,
                'cover_color' => 'cover-c',
            ])
            ->assertRedirect(route('admin.buku.index'));

        $buku = Buku::where('kode_buku', 'BK-100')->first();
        $this->assertSame(3, $buku->stok_tersedia);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.buku.update', $buku), [
                'kode_buku' => 'BK-100',
                'judul' => 'Pemodelan Data Lanjut',
                'penulis' => 'N. Putri',
                'kategori_id' => $kategori->id,
                'stok_total' => 5,
                'cover_color' => 'cover-c',
                'status_buku' => 'tersedia',
            ]);

        $buku->refresh();
        $this->assertSame('Pemodelan Data Lanjut', $buku->judul);
        $this->assertSame(5, $buku->stok_tersedia);
    }

    public function test_hapus_buku_adalah_soft_delete(): void
    {
        $kategori = KategoriBuku::create(['nama_kategori' => 'Umum']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'BK-200',
            'judul' => 'Literasi Digital',
            'penulis' => 'M. Firdaus',
            'stok_total' => 1,
            'stok_tersedia' => 1,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.buku.destroy', $buku))
            ->assertSessionHas('success');

        $this->assertSame('tidak_aktif', $buku->fresh()->status_buku);
        $this->assertDatabaseHas('buku', ['id' => $buku->id]);
    }

    public function test_hapus_buku_diblokir_saat_ada_pinjaman_aktif(): void
    {
        $kategori = KategoriBuku::create(['nama_kategori' => 'Umum']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'BK-300',
            'judul' => 'Jaringan Komputer Dasar',
            'penulis' => 'T. Nugroho',
            'stok_total' => 2,
            'stok_tersedia' => 2,
        ]);

        $anggota = Anggota::create([
            'nama_lengkap' => 'Ryan Jasper',
            'username' => 'ryan',
            'email' => 'ryan@perpusapp.id',
            'password' => 'password',
            'status_akun' => 'aktif',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $this->actingAs($anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $buku->id]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.buku.destroy', $buku))
            ->assertSessionHas('error');

        $this->assertNotSame('tidak_aktif', $buku->fresh()->status_buku);
    }

    public function test_admin_verifikasi_anggota_mengirim_notifikasi(): void
    {
        $anggota = Anggota::create([
            'nama_lengkap' => 'Nadia Ayu',
            'username' => 'nadia',
            'email' => 'nadia@perpusapp.id',
            'password' => 'password',
            'status_akun' => 'menunggu',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.anggota.status', $anggota), ['status_akun' => 'aktif'])
            ->assertSessionHas('success');

        $this->assertSame('aktif', $anggota->fresh()->status_akun);
        $this->assertSame(1, $anggota->notifikasi()->where('tipe', 'verifikasi')->count());
    }

    public function test_admin_update_setting(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.setting.update'), [
                'tarif_denda_harian' => 10000,
                'durasi_peminjaman' => 14,
                'batas_peminjaman' => 5,
                'verifikasi_akun' => 'nonaktif',
            ])
            ->assertRedirect(route('admin.setting.index'));

        $this->assertSame('10000', setting('tarif_denda_harian'));
        $this->assertSame('14', setting('durasi_peminjaman'));
        $this->assertSame('nonaktif', setting('verifikasi_akun'));
    }

    public function test_kategori_crud(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.kategori.store'), [
                'nama_kategori' => 'Interaksi Manusia Komputer',
                'deskripsi' => 'Desain UI dan UX.',
            ]);

        $kategori = KategoriBuku::where('nama_kategori', 'Interaksi Manusia Komputer')->first();
        $this->assertNotNull($kategori);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.kategori.update', $kategori), [
                'nama_kategori' => 'IMK',
                'deskripsi' => 'Desain UI, UX, usability.',
            ]);

        $this->assertSame('IMK', $kategori->fresh()->nama_kategori);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.kategori.destroy', $kategori));

        $this->assertDatabaseMissing('kategori_buku', ['id' => $kategori->id]);
    }
}
