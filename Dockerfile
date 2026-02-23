FROM php:8.2-apache

# -----------------------------
# Dependencias necesarias
# -----------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------
# Activar mod_rewrite
# -----------------------------
RUN a2enmod rewrite

# -----------------------------
# Configurar DocumentRoot SOLO en el VirtualHost
# -----------------------------
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# -----------------------------
# Instalar Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# Copiar proyecto
# -----------------------------
WORKDIR /var/www/html
COPY . .

# -----------------------------
# Instalar dependencias producción
# -----------------------------
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# -----------------------------
# Permisos correctos
# -----------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 writable

EXPOSE 80

CMD ["apache2-foreground"]
