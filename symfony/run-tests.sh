#!/usr/bin/env sh

./bin/console doctrine:database:drop --force && \
./bin/console doctrine:database:create --if-not-exists && \
yes | ./bin/console doctrine:migration:migrate && \
yes | ./bin/console doctrine:fixtures:load &&
./bin/phpunit