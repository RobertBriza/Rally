#!/bin/bash

set -e

mkdir -p $SECRET_DIR && chmod -R 0777 $SECRET_DIR

cd /var/www/html
composer install
php bin/console app:generate-config
php bin/console migrations:migrate
php bin/console doctrine:fixtures:load --no-interaction

echo "Done!"

docker-php-entrypoint $@
