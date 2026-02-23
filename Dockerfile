FROM php:8.2-apache

# 1. Dependencias
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# 2. Configuración de Apache y Limpieza de MPM
# Desactivamos módulos conflictivos y forzamos prefork antes de cualquier otra cosa
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3. Código y Composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Permisos
RUN chown -R www-data:www-data /var/www/html && chmod -R 777 writable

# 5. Script de arranque
RUN chmod +x /var/www/html/start.sh
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]