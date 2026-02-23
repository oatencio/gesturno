FROM php:8.2-apache

# -----------------------------
# Dependencias del sistema
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
# Configurar DocumentRoot para CI4
# -----------------------------
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' >> /etc/apache2/apache2.conf

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
# Instalar dependencias optimizadas para producción
# -----------------------------
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# -----------------------------
# Permisos seguros (sin 777)
# -----------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 writable

# -----------------------------
# Exponer puerto
# -----------------------------
EXPOSE 80

CMD ["apache2-foreground"]
