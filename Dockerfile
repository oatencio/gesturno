FROM php:8.2-apache

# 1. Dependencias (Se mantiene igual)
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# 2. Configuración de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# 3. Composer y Código
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Permisos
RUN chown -R www-data:www-data /var/www/html && chmod -R 777 writable

# --- LAS NUEVAS LÍNEAS COMIENZAN AQUÍ ---

# 5. Copiamos el archivo start.sh que creamos anteriormente
# Asegúrate de que el archivo start.sh esté en la misma carpeta que este Dockerfile
COPY start.sh /usr/local/bin/start.sh

# 6. Le damos permisos de ejecución
RUN chmod +x /usr/local/bin/start.sh

# 7. Exponemos el puerto (Railway usará este como referencia inicial)
EXPOSE 80

# 8. Ejecutamos el script como comando principal
CMD ["/usr/local/bin/start.sh"]