#!/usr/bin/env sh

composer install && \
./bin/console doctrine:database:drop --force && \
./bin/console doctrine:database:create --if-not-exists && \
yes | ./bin/console doctrine:migration:migrate && \
yes | ./bin/console doctrine:fixtures:load &&
php-fpm7 -F