#! /bin/env bash
#
# This script is run by Travis CI before the test script.
# It updates composer

echo -- Updating Composer
composer self-update

# those are not needed
#
# echo -- Installing the dependencies
# composer install --dev
