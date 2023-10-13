#!/bin/bash

set -e

cd /var/www/html
composer install
php bin/console app:generate-config
php bin/console migrations:migrate
php bin/console doctrine:fixtures:load --no-interaction

echo "Done!"

docker-php-entrypoint $@
