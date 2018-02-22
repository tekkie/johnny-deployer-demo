#!/usr/bin/env bash

# ensure environment vars are defined
cp bot/.env.dist bot/.env
cp brains/.env.dist brains/.env

# kickoff all containers

docker-compose rm -f brains-php-fpm nginx

docker-compose build
docker-compose up -d

docker-compose exec brains-php-fpm /var/www/johnny/the-brains/bin/setup_brains.sh
