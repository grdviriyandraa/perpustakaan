#!/bin/sh
# Skrip start produksi untuk Railway.
set -e

# Railway menyuntikkan $PORT. Sesuaikan port Apache agar cocok (default 8080).
PORT="${PORT:-8080}"
sed -i "s/Listen [0-9]*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:[0-9]*>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Siapkan database lalu jalankan Apache di foreground.
php artisan migrate --force
php artisan db:seed --force
php artisan config:clear

exec apache2-foreground
