#! /bin/bash bash
#
# This script is run by Travis CI before the test script.
# It updates composer

#echo -- Updating Composer
#wget http://getcomposer.org/composer.phar
php composer.phar require satooshi/php-coveralls:dev-master --dev --no-progress --prefer-source

# composer self-update

# those are not needed
#
# echo -- Installing the dependencies
# composer install --dev
