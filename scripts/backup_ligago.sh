#!/bin/bash

FECHA=$(date +"%Y-%m-%d_%H-%M")
DIRECTORIO_DESTINO="/home/usuario/backups"
BASE_DE_DATOS="ligago_db"
NOMBRE_ARCHIVO="backup_ligago_$FECHA.sql"
RUTA_COMPLETA="$DIRECTORIO_DESTINO/$NOMBRE_ARCHIVO"

mkdir -p $DIRECTORIO_DESTINO

echo "[$(date +"%H:%M:%S")] Iniciando copia de seguridad de la base de datos: $BASE_DE_DATOS..."
sudo -u postgres pg_dump $BASE_DE_DATOS > $RUTA_COMPLETA

echo "[$(date +"%H:%M:%S")] Comprimiendo el archivo de volcado..."
gzip $RUTA_COMPLETA

find $DIRECTORIO_DESTINO -type f -name "*.gz" -mtime +7 -delete

echo "[$(date +"%H:%M:%S")] Copia de seguridad finalizada y comprimida en: $RUTA_COMPLETA.gz"