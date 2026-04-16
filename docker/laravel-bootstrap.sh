#!/usr/bin/env bash
set -euo pipefail

DATADIR="${MYSQL_DATADIR:-/var/lib/mysql}"
MARKER="${DATADIR}/.laravel_mysql_ready"
DB_NAME="${MYSQL_DATABASE:-laravel}"
DB_USER="${MYSQL_USER:-laravel}"
DB_PASS="${MYSQL_PASSWORD:-secret}"
SOCKET="${MYSQL_SOCKET:-/run/mysqld/mysqld.sock}"

for _ in $(seq 1 90); do
  if mysqladmin ping -uroot --socket="${SOCKET}" --silent 2>/dev/null; then
    break
  fi
  sleep 1
done

if ! mysqladmin ping -uroot --socket="${SOCKET}" --silent 2>/dev/null; then
  echo "laravel-bootstrap: MySQL not reachable."
  exit 1
fi

if [[ ! -f "${MARKER}" ]]; then
  mysql -uroot --socket="${SOCKET}" <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
  touch "${MARKER}"
fi

cd /var/www/html
# .env may use sqlite with MySQL lines commented; Laravel defaults missing DB_* to root/no password.
sed -i \
  -e "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" \
  -e "s/^# *DB_HOST=.*/DB_HOST=127.0.0.1/" \
  -e "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" \
  -e "s/^# *DB_PORT=.*/DB_PORT=3306/" \
  -e "s/^DB_PORT=.*/DB_PORT=3306/" \
  -e "s/^# *DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" \
  -e "s/^DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" \
  -e "s/^# *DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" \
  -e "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" \
  -e "s/^# *DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" \
  -e "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" \
  .env
grep -q '^DB_HOST=' .env || echo 'DB_HOST=127.0.0.1' >> .env
grep -q '^DB_PORT=' .env || echo 'DB_PORT=3306' >> .env
grep -q '^DB_DATABASE=' .env || echo "DB_DATABASE=${DB_NAME}" >> .env
grep -q '^DB_USERNAME=' .env || echo "DB_USERNAME=${DB_USER}" >> .env
grep -q '^DB_PASSWORD=' .env || echo "DB_PASSWORD=${DB_PASS}" >> .env

php artisan config:clear
php artisan migrate --force

exit 0
