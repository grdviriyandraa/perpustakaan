#!/bin/sh
# Skrip start produksi untuk Railway (server bawaan Laravel).
set -e

# Private network Railway kadang telat siap beberapa detik setelah container
# start. Coba migrate beberapa kali sebelum menyerah.
migrasi_ok=0
for i in 1 2 3 4 5 6 7 8 9 10; do
    if php artisan migrate --force; then
        migrasi_ok=1
        break
    fi
    echo "Database belum siap, mencoba lagi dalam 3 detik (percobaan ${i}/10)..."
    sleep 3
done

if [ "$migrasi_ok" != "1" ]; then
    echo "GAGAL terhubung ke database setelah 10 percobaan. Cek variabel DB_* di service aplikasi."
    exit 1
fi

php artisan db:seed --force
php artisan config:clear

# Railway mengarahkan trafik ke $PORT. Server harus mendengarkan di port itu.
PORT="${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
