#!/bin/sh

echo "Running bl-php's entrypoint file..."

echo "Modifying user (hack for mac)..."
usermod -u 1000 www-data #a hack for macs

echo "Waiting for bl-mysql-service..."
while ! mysqladmin ping -h"bl-mysql-service" --silent; do
	echo "Still waiting for bl-mysql-service..."
    sleep 1
done
echo "bl-mysql-service is running..."

echo "Starting up mysql..."
/etc/init.d/mysql start

echo "updating sql_mode..."
mysql -h bl-mysql-service -u root -p123 -se "SET GLOBAL sql_mode = 'ALLOW_INVALID_DATES';"

echo "Setup db migrations..."
php artisan migrate:install

echo "Running db migrations..."
php artisan --verbose migrate --seed

echo "Deleting existing apache pid if present..."
if [ -f "$APACHE_PID_FILE" ]; then
	rm "$APACHE_PID_FILE"
fi

echo "Bestline is ready!"
/usr/sbin/apache2ctl -D FOREGROUND