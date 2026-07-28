#!/bin/bash
# Install PHP and MariaDB if missing (for runtime in production)
if ! command -v php &> /dev/null; then
  apt-get update
  DEBIAN_FRONTEND=noninteractive apt-get install -y -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" php-cli php-mysql mariadb-server
fi

# Initialize and Start MySQL
chown -R mysql:mysql /var/lib/mysql
if [ ! -d /var/lib/mysql/alfoz_erp_db ]; then
  mysql_install_db --user=mysql --datadir=/var/lib/mysql
  mariadbd-safe --user=mysql --datadir=/var/lib/mysql &
  sleep 5
  mysql -u root -e "CREATE DATABASE IF NOT EXISTS alfoz_erp_db;"
  mysql -u root -e "CREATE USER IF NOT EXISTS 'alfoz_erp_user'@'localhost' IDENTIFIED BY 'AlFozSecurePass2026!';"
  mysql -u root -e "GRANT ALL PRIVILEGES ON alfoz_erp_db.* TO 'alfoz_erp_user'@'localhost';"
  mysql -u root -e "FLUSH PRIVILEGES;"
  if [ -f database/schema.sql ]; then
    mysql -u root alfoz_erp_db < database/schema.sql
  fi
  if [ -f database/database.sql ]; then
    mysql -u root alfoz_erp_db < database/database.sql
  fi
else
  mariadbd-safe --user=mysql --datadir=/var/lib/mysql &
  sleep 3
fi

# Start PHP built-in server
php -S 0.0.0.0:3000
