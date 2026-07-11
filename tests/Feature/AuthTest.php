<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Notifikasi;
use Database\Seeders\SystemSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemSettingSeeder::class);
    }

    private function buatAdmin(): Admin
    {
        return Admin::create([
            'nama_admin' => 'Admin Perpus',
            'username' => 'admin',
            'email' => 'admin@perpusapp.id',
            'password' => 'admin123',
        ]);
    }

    private function buatAnggota(string $status = 'aktif'): Anggota
    {
        return Anggota::create([
            'nama_lengkap' => 'Ryan Jasper',
            'username' => 'ryan',
            'email' => 'ryan@perpusapp.id',
            'password' => 'password',
            'status_akun' => $status,
            'tanggal_daftar' => now()->toDateString(),
        ]);
    }

    public function test_halaman_login_tampil(): void
    {
        $this->get('/')->assertOk()->assertSee('Masuk');
    }

    public function test_register_membuat_anggota_menunggu_dan_notifikasi_admin(): void
    {
        $this->buatAdmin();

        $response = $this->post('/register', [
            'nama_lengkap' => 'Nadia Ayu',
            'username' => 'nadia',
            'email' => 'nadia@perpusapp.id',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'no_telp' => '0812',
            'alamat' => 'Jl. Cendekia 8',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('anggota', ['username' => 'nadia', 'status_akun' => 'menunggu']);
        $this->assertSame(1, Notifikasi::whereNotNull('admin_id')->where('tipe', 'verifikasi')->count());
    }

    public function test_register_langsung_aktif_saat_verifikasi_nonaktif(): void
    {
        \App\Models\SystemSetting::where('key', 'verifikasi_akun')->update(['value' => 'nonaktif']);

        $this->post('/register', [
            'nama_lengkap' => 'Bima Putra',
            'username' => 'bima',
            'email' => 'bima@perpusapp.id',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $this->assertDatabaseHas('anggota', ['username' => 'bima', 'status_akun' => 'aktif']);
    }

    public function test_anggota_menunggu_tidak_bisa_login(): void
    {
        $this->buatAnggota('menunggu');

        $this->post('/login', ['login' => 'ryan', 'password' => 'password'])
            ->assertSessionHas('error');

        $this->assertGuest('anggota');
    }

    public function test_anggota_aktif_bisa_login_dengan_username_atau_email(): void
    {
        $this->buatAnggota();

        $this->post('/login', ['login' => 'ryan', 'password' => 'password'])
            ->assertRedirect(route('anggota.dashboard'));
        $this->assertAuthenticated('anggota');

        auth('anggota')->logout();

        $this->post('/login', ['login' => 'ryan@perpusapp.id', 'password' => 'password'])
            ->assertRedirect(route('anggota.dashboard'));
        $this->assertAuthenticated('anggota');
    }

    public function test_admin_bisa_login_dan_akses_dashboard(): void
    {
        $this->buatAdmin();

        $this->post('/admin/login', ['login' => 'admin', 'password' => 'admin123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated('admin');
        $this->get('/admin/dashboard')->assertOk();
    }

    public function test_guard_terpisah_anggota_tidak_bisa_akses_admin(): void
    {
        $anggota = $this->buatAnggota();

        $this->actingAs($anggota, 'anggota')
            ->get('/admin/dashboard')
            ->assertRedirect(route('admin.login'));
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/anggota/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/buku')->assertRedirect(route('admin.login'));
    }
}
