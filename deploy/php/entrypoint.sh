#!/bin/bash

set -e

mkdir -p $SECRET_DIR && chmod -R 0777 $SECRET_DIR

cd /var/www/html
composer install
php bin/console app:generate-config
php bin/console migrations:migrate

echo "Done!"

docker-php-entrypoint $@
