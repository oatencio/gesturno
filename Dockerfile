FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Activar mod_rewrite
RUN a2enmod rewrite

# Configurar DocumentRoot para CodeIgniter
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Cambiamos los paths de Apache de forma más segura
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permitir .htaccess en el directorio public
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 writable

# --- ESTA ES LA PARTE CLAVE PARA RAILWAY Y LOCAL ---
# En lugar de editar los archivos internos con sed, forzamos a Apache
# a usar la variable PORT si existe, sino usa el 80.
RUN echo "Listen \${PORT}" > /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# Valor por defecto para que funcione en local sin configurar nada
ENV PORT=80

EXPOSE ${PORT}

CMD ["apache2-foreground"]