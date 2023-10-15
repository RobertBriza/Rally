#!/bin/bash

set -e

until nc -z -v -w30 $MYSQL_HOST 3306; do
  echo "Waiting for database connection..."
  sleep 1
done

cd /var/www/html
composer install
php bin/console app:generate-config
php bin/console migrations:migrate
php bin/console doctrine:fixtures:load --no-interaction

echo "Done!"

docker-php-entrypoint $@
