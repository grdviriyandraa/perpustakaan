#!/bin/sh
# Skrip start produksi untuk Railway.
set -e

# Railway menyuntikkan $PORT. Sesuaikan port Apache agar cocok (default 8080).
PORT="${PORT:-8080}"
sed -i "s/Listen [0-9]*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:[0-9]*>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

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

exec apache2-foreground
