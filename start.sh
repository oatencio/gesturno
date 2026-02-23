#!/bin/sh

# Salir inmediatamente si un comando falla
set -e

echo "🚀 Iniciando proceso de despliegue en ambiente: $CI_ENVIRONMENT"

# 1. Configuración de Base de Datos (Solo en Producción/Railway)
if [ "$CI_ENVIRONMENT" = "production" ]; then
    echo "- Esperando a que la base de datos esté lista..."
    sleep 5 # Pausa de seguridad para que MySQL levante
    
    echo "- Ejecutando migraciones pendientes..."
    # Usamos el flag --force porque en producción CI4 lo pide
    php spark migrate --all --force || echo "⚠️ Migraciones saltadas."

    echo "- Verificando datos iniciales..."
    php spark db:seed MainSeeder --force || echo "⚠️ Seeder saltado."
fi

# 2. Configuración Dinámica de Apache
# Usamos el puerto asignado por Railway ($PORT) o el 80 por defecto para local.
LISTEN_PORT=${PORT:-80}

echo "- Configurando Apache para escuchar en el puerto: $LISTEN_PORT"

# Modificar ports.conf
sed -i "s/Listen 80/Listen $LISTEN_PORT/g" /etc/apache2/ports.conf

# Modificar el VirtualHost por defecto
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$LISTEN_PORT>/g" /etc/apache2/sites-available/000-default.conf

# 3. Arrancar Apache en primer plano
echo "✅ Todo listo. Arrancando Apache..."
exec apache2-foreground