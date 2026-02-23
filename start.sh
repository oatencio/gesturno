#!/bin/sh
# Configuración de puerto para Railway
LISTEN_PORT=${PORT:-8080}
sed -i "s/Listen 80/Listen $LISTEN_PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$LISTEN_PORT>/g" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground