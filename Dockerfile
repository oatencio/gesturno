FROM php:8.2-apache

# 1. Instalación de dependencias (igual que antes)
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# 2. Configuración de Apache y CodeIgniter
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3. Composer y Código
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 writable

# --- CAMBIO CRÍTICO AQUÍ ---
# Copiamos el script de entrada y le damos permisos
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Railway necesita saber qué puerto exponer, pero el script lo manejará
EXPOSE 80

# Usamos nuestro script como punto de inicio
ENTRYPOINT ["docker-entrypoint.sh"]