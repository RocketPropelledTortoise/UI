#!/usr/bin/env bash

if [ ! -d build ]; then
  mkdir build;
fi

wget -O build/phpunit https://phar.phpunit.de/phpunit-8.phar
chmod +x build/phpunit
