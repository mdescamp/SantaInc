#!/bin/bash
if [ "${1:-foo}" == "reset" ]; then
  php bin/console doctrine:database:drop --force
fi
php bin/console doctrine:database:create
php bin/console doctrine:m:m
php bin/console d:f:l
