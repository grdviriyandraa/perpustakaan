<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use Database\Seeders\SystemSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanFlowTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    private Anggota $anggota;

    private Buku $buku;

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

        $this->anggota = Anggota::create([
            'nama_lengkap' => 'Ryan Jasper',
            'username' => 'ryan',
            'email' => 'ryan@perpusapp.id',
            'password' => 'password',
            'status_akun' => 'aktif',
            'tanggal_daftar' => now()->toDateString(),
        ]);

        $kategori = KategoriBuku::create(['nama_kategori' => 'Sistem Informasi']);

        $this->buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'BK-001',
            'judul' => 'Dasar Sistem Informasi',
            'penulis' => 'R. Santoso',
            'stok_total' => 2,
            'stok_tersedia' => 2,
        ]);
    }

    public function test_alur_peminjaman_lengkap_sampai_pengembalian_terlambat(): void
    {
        // 1. Anggota mengajukan peminjaman — stok berkurang.
        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id])
            ->assertRedirect(route('anggota.peminjaman.status'));

        $this->assertSame(1, $this->buku->fresh()->stok_tersedia);

        $peminjaman = Peminjaman::first();
        $this->assertSame('menunggu', $peminjaman->status);
        $this->assertSame($this->buku->id, $peminjaman->detail->first()->buku_id);

        // 2. Admin menyetujui — tanggal pinjam & jatuh tempo terisi.
        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.peminjaman.validasi', $peminjaman), ['action' => 'setujui'])
            ->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertSame('disetujui', $peminjaman->status);
        $this->assertSame(
            now()->addDays(7)->toDateString(),
            $peminjaman->tanggal_jatuh_tempo->toDateString()
        );

        // 3. Admin konfirmasi buku diambil.
        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.peminjaman.konfirmasi', $peminjaman));

        $this->assertSame('dipinjam', $peminjaman->fresh()->status);

        // 4. Pengembalian terlambat 3 hari → denda 3 × 5000.
        $tanggalKembali = now()->addDays(10)->toDateString();

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengembalian.store', $peminjaman), [
                'tanggal_pengembalian' => $tanggalKembali,
            ])
            ->assertRedirect(route('admin.pengembalian.index'));

        $peminjaman->refresh();
        $this->assertSame('selesai', $peminjaman->status);
        $this->assertSame(2, $this->buku->fresh()->stok_tersedia);

        $denda = Denda::first();
        $this->assertNotNull($denda);
        $this->assertSame(3, $denda->jumlah_hari_terlambat);
        $this->assertSame(15000, $denda->total_denda);
        $this->assertSame('belum', $denda->status_bayar);

        // 5. Denda belum dibayar memblokir pengajuan baru.
        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id])
            ->assertSessionHas('error');

        $this->assertSame(1, Peminjaman::count());

        // 6. Admin menandai lunas → anggota bisa meminjam lagi.
        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.denda.lunas', $denda));

        $this->assertSame('lunas', $denda->fresh()->status_bayar);

        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id])
            ->assertRedirect(route('anggota.peminjaman.status'));

        $this->assertSame(2, Peminjaman::count());
    }

    public function test_pengembalian_tepat_waktu_tanpa_denda(): void
    {
        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id]);

        $peminjaman = Peminjaman::first();

        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.peminjaman.validasi', $peminjaman), ['action' => 'setujui']);
        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.peminjaman.konfirmasi', $peminjaman));

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengembalian.store', $peminjaman), [
                'tanggal_pengembalian' => now()->addDays(5)->toDateString(),
            ]);

        $this->assertSame('selesai', $peminjaman->fresh()->status);
        $this->assertSame('tepat_waktu', $peminjaman->fresh()->pengembalian->status_pengembalian);
        $this->assertSame(0, Denda::count());
    }

    public function test_pengajuan_ditolak_mengembalikan_stok(): void
    {
        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id]);

        $this->assertSame(1, $this->buku->fresh()->stok_tersedia);

        $peminjaman = Peminjaman::first();

        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.peminjaman.validasi', $peminjaman), [
                'action' => 'tolak',
                'catatan' => 'Stok dialokasikan untuk kelas.',
            ]);

        $this->assertSame('ditolak', $peminjaman->fresh()->status);
        $this->assertSame(2, $this->buku->fresh()->stok_tersedia);
    }

    public function test_stok_habis_menolak_pengajuan(): void
    {
        $this->buku->update(['stok_tersedia' => 0, 'status_buku' => 'dipinjam']);

        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id])
            ->assertSessionHas('error');

        $this->assertSame(0, Peminjaman::count());
    }

    public function test_batas_peminjaman_dibatasi_setting(): void
    {
        \App\Models\SystemSetting::where('key', 'batas_peminjaman')->update(['value' => '1']);

        $kategori = KategoriBuku::first();
        $bukuLain = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'BK-002',
            'judul' => 'Analisis Sistem Modern',
            'penulis' => 'A. Wijaya',
            'stok_total' => 1,
            'stok_tersedia' => 1,
        ]);

        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $this->buku->id]);

        $this->actingAs($this->anggota, 'anggota')
            ->post(route('anggota.peminjaman.store'), ['buku_id' => $bukuLain->id])
            ->assertSessionHas('error');

        $this->assertSame(1, Peminjaman::count());
    }
}
