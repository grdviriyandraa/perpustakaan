# PerpusApp

Sistem informasi peminjaman buku perpustakaan berbasis web — Laravel 11, Blade, dan CSS custom (tanpa framework CSS). Mendigitalisasi alur administrasi perpustakaan: pendaftaran anggota, katalog, pengajuan peminjaman, validasi, pencatatan pengembalian, perhitungan denda, dan laporan.

## Fitur

**Anggota**
- Registrasi dengan verifikasi admin (dapat dimatikan lewat pengaturan)
- Katalog buku: pencarian, filter kategori & status, pagination
- Pengajuan peminjaman dengan pengecekan syarat otomatis (status akun, stok, batas pinjam, denda tertunggak)
- Status & riwayat peminjaman, informasi denda, notifikasi, wishlist, profil

**Admin**
- Dashboard ringkasan (anggota aktif, pinjaman aktif, keterlambatan, denda)
- CRUD buku (soft delete) & kategori
- Verifikasi / tolak / nonaktifkan anggota
- Validasi peminjaman: setujui, tolak (stok kembali), konfirmasi pengambilan
- Pencatatan pengembalian dengan denda otomatis (tarif snapshot)
- Kelola denda (tandai lunas), laporan periode + grafik + ekspor PDF
- Cetak kartu anggota (PDF), pengaturan sistem, notifikasi

## Arsitektur

- **Dual guard auth**: guard `anggota` dan `admin` terpisah (bukan role column)
- **Service layer**: `PeminjamanService`, `DendaService`, `NotifikasiService`
- **Stok atomic**: transaksi DB + `lockForUpdate` saat pengajuan/pengembalian
- **Denda snapshot**: tarif disimpan per denda agar perubahan setting tidak mengubah denda lama

## Menjalankan Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # default: SQLite
php artisan migrate --seed
php artisan serve
```

Akun hasil seeder:

| Peran   | Login   | Password   | URL            |
|---------|---------|------------|----------------|
| Admin   | `admin` | `admin123` | `/admin/login` |
| Anggota | `ryan`  | `password` | `/`            |

Jalankan test: `php artisan test`

## Deployment (Railway)

Repo sudah menyertakan `nixpacks.toml` dan `railway.json`.

1. Buat project Railway, tambahkan service **MySQL**
2. Tambahkan repo GitHub ini sebagai service
3. Set environment variables:

```env
APP_NAME=PerpusApp
APP_ENV=production
APP_KEY=            # php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://<domain>.up.railway.app

DB_CONNECTION=mysql
DB_HOST=            # dari service MySQL Railway
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=        # dari service MySQL Railway

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

4. Deploy — start command otomatis menjalankan `migrate --force` dan `db:seed --force`.
