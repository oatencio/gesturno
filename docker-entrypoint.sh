#!/bin/bash
set -e

# Si no hay PORT definido (como en local), usamos el 80
: ${PORT:=80}

# Modificamos los archivos de Apache con el valor real de la variable
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# Ejecutamos el comando original de Apache
exec apache2-foreground