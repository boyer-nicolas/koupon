#!/bin/bash
set -e

if [ ! -d "./bin/console/vendor" ]; then
    cd bin/console
    composer install
    cd ../..
fi

php bin/console/app.php "$@"
