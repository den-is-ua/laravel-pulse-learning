#!/usr/bin/env bash
set -euo pipefail

export MYSQL_DATADIR="${MYSQL_DATADIR:-/var/lib/mysql}"

init_datadir() {
  if [[ ! -d "${MYSQL_DATADIR}/mysql" ]]; then
    mkdir -p "${MYSQL_DATADIR}"
    chown -R mysql:mysql "${MYSQL_DATADIR}"
    mariadb-install-db --user=mysql --datadir="${MYSQL_DATADIR}" --skip-test-db
  fi
}

mkdir -p /run/mysqld
chown mysql:mysql /run/mysqld

init_datadir

exec "$@"
