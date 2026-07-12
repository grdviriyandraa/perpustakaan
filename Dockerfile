# PerpusApp — image produksi berbasis PHP 8.4 CLI + server bawaan Laravel.
# (Tanpa Apache: menghindari isu MPM sepenuhnya.)
FROM php:8.4-cli

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

WORKDIR /var/www/html

# Salin seluruh kode lalu pasang dependensi produksi.
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chmod +x docker/railway-start.sh

EXPOSE 8080

# Migrate + seed lalu jalankan server. Skrip menyesuaikan port ke $PORT.
CMD ["sh", "docker/railway-start.sh"]
