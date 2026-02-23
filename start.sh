#!/bin/sh
# No usar 'set -e' aquí para que un fallo en la DB no mate todo el contenedor
# y podamos ver los logs de Apache después.

echo "🚀 Iniciando proceso de despliegue en ambiente: $CI_ENVIRONMENT"

if [ "$CI_ENVIRONMENT" = "production" ]; then
    echo "- Esperando 10 segundos a que la red y DB estabilicen..."
    sleep 10
    
    echo "- Intentando migraciones (con --force)..."
    # Forzamos a que use el puerto y host de las variables de entorno
    php spark migrate --all --force
    
    echo "- Intentando seeders..."
    php spark db:seed MainSeeder --force
fi

# Configuración del puerto
LISTEN_PORT=${PORT:-8080}
echo "- Configurando puerto: $LISTEN_PORT"
sed -i "s/Listen 80/Listen $LISTEN_PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$LISTEN_PORT>/g" /etc/apache2/sites-available/000-default.conf

echo "✅ Ejecutando apache2-foreground..."
exec apache2-foreground