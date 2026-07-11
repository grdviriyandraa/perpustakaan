# PerpusApp — image produksi berbasis PHP 8.2 resmi.
FROM php:8.2-cli

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

WORKDIR /app

# Salin seluruh kode lalu pasang dependensi produksi.
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

EXPOSE 8080

# Start command produksi diatur oleh railway.json (migrate + seed + serve).
# CMD ini menjadi fallback bila dijalankan langsung.
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
