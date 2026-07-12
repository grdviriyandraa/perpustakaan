# PerpusApp — image produksi berbasis PHP 8.4 + Apache.
FROM php:8.4-apache

# Pemaksa rebuild: naikkan nilai ini untuk membatalkan cache Docker sepenuhnya.
ARG CACHEBUST=20260711-2
RUN echo "cachebust=${CACHEBUST}"

# mod_php butuh mpm_prefork (bukan event/worker). Hapus SEMUA symlink MPM lebih
# dulu (a2dismod menolak menonaktifkan MPM aktif), lalu aktifkan hanya prefork
# plus mod_rewrite untuk front controller Laravel (public/.htaccess).
RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && a2enmod mpm_prefork rewrite

# Dependensi sistem untuk membangun extension PHP.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Extension PHP yang dibutuhkan Laravel + DomPDF + MySQL.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo pdo_mysql mbstring gd zip bcmath

# Composer dari image resmi.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Arahkan document root Apache ke folder public/ Laravel dan izinkan .htaccess.
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

WORKDIR /var/www/html

# Salin seluruh kode lalu pasang dependensi produksi.
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/railway-start.sh

EXPOSE 8080

# Jalankan migrate + seed lalu Apache (foreground). Skrip menyesuaikan port ke $PORT.
CMD ["sh", "docker/railway-start.sh"]
