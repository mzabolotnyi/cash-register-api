#!/bin/bash

composer install
echo "Composer install done"

php bin/console d:s:u --force
echo "Database was updated successfully"

php bin/console d:m:m --no-interaction
echo "Migrations was migrated successfully"
