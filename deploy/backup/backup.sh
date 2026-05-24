#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/iksaudi}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/iksaudi}"
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
mkdir -p "${BACKUP_DIR}"

cd "${APP_DIR}"

if [[ -f .env ]]; then
  DB_CONNECTION="$(grep '^DB_CONNECTION=' .env | cut -d= -f2- | tr -d '"')"
  DB_DATABASE="$(grep '^DB_DATABASE=' .env | cut -d= -f2- | tr -d '"')"
  DB_USERNAME="$(grep '^DB_USERNAME=' .env | cut -d= -f2- | tr -d '"')"
  DB_PASSWORD="$(grep '^DB_PASSWORD=' .env | cut -d= -f2- | tr -d '"')"
fi

if [[ "${DB_CONNECTION:-}" == "mysql" ]]; then
  mysqldump -u"${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" \
    | gzip > "${BACKUP_DIR}/db_${TIMESTAMP}.sql.gz"
fi

tar -czf "${BACKUP_DIR}/storage_${TIMESTAMP}.tar.gz" storage/app/public

find "${BACKUP_DIR}" -type f -mtime +14 -delete

echo "Backup completed: ${BACKUP_DIR}"
