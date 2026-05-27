#!/bin/bash

# ==============================================================================
# Asistente de Restauración Interactiva para LigaGo
# ==============================================================================

DIRECTORIO_DESTINO="/home/usuario/backups"
BASE_DE_DATOS="ligago_db"

clear
echo "================================================="
echo "   🔄 ASISTENTE DE RESTAURACIÓN - LIGAGO"
echo "================================================="
echo ""

shopt -s nullglob
BACKUPS=($DIRECTORIO_DESTINO/*.sql.gz)

if [ ${#BACKUPS[@]} -eq 0 ]; then
    echo "❌ No se han encontrado copias de seguridad en $DIRECTORIO_DESTINO."
    echo "Saliendo del asistente..."
    exit 1
fi

echo "Se han encontrado las siguientes copias de seguridad:"
echo "-------------------------------------------------"

contador=1
for backup in "${BACKUPS[@]}"; do
    
    nombre_archivo=$(basename "$backup")
    echo "  $contador) $nombre_archivo"
    ((contador++))

done

echo "-------------------------------------------------"
echo "Escribe el número de la copia que quieres restaurar (o pulsa Ctrl+C para cancelar):"
read -p "Opción: " opcion

if ! [[ "$opcion" =~ ^[0-9]+$ ]] || [ "$opcion" -lt 1 ] || [ "$opcion" -gt ${#BACKUPS[@]} ]; then
    echo "❌ Opción no válida. Abortando operación."
    exit 1
fi

ARCHIVO_ELEGIDO="${BACKUPS[$((opcion-1))]}"
NOMBRE_ELEGIDO=$(basename "$ARCHIVO_ELEGIDO")

echo ""
echo "⚠️ ATENCIÓN: Vas a restaurar la copia '$NOMBRE_ELEGIDO'."
echo "Esto borrará la base de datos actual y la sobreescribirá por completo."
read -p "¿Estás completamente seguro? (s/n): " confirmacion

if [[ "$confirmacion" != "s" && "$confirmacion" != "S" ]]; then
    echo "Operación cancelada por el usuario. No se ha tocado nada."
    exit 0
fi

echo ""
echo "⏳ Iniciando proceso de restauración..."

ARCHIVO_SQL="/tmp/restore_temp.sql"
echo "[1/3] Extrayendo datos..."
zcat "$ARCHIVO_ELEGIDO" > "$ARCHIVO_SQL"

echo "[2/3] Limpiando base de datos actual..."
sudo -u postgres dropdb --if-exists $BASE_DE_DATOS
sudo -u postgres createdb $BASE_DE_DATOS

echo "[3/3] Inyectando copia de seguridad..."
sudo -u postgres psql $BASE_DE_DATOS < "$ARCHIVO_SQL"

rm "$ARCHIVO_SQL"

echo ""
echo "✅ ¡RESTAURACIÓN COMPLETADA CON ÉXITO!"
echo "La base de datos '$BASE_DE_DATOS' vuelve a estar operativa."