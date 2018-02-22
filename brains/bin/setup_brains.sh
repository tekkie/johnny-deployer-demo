#!/usr/bin/env bash

cd /var/www/johnny/the-brains
./bin/console cache:clear
./bin/console cache:warmup
