#!/bin/bash
set -e

if [ ! -d "app" ]; then
    echo "No application created. Cannot execute scripts."
    exit 1
fi

if [[ ! -f "app/.env" && -f ".env.template" ]]; then
    echo "Creating .env file..."
    if ! cp .env.template app/.env; then
        echo "Cannot create .env file. Please create it manually."
        exit 0
    fi
fi

if [[ ! -f "app/.db.local.env" && -f ".db.env.template" ]]; then
    echo "Creating .db.local.env file..."
    if ! cp .db.env.template app/.db.local.env; then
        echo "Cannot create .db.local.env file. Please create it manually."
        exit 0
    fi
fi

# Set filepaths
export SQL_FILE_PATH="sql"

# Set container names
export SERVER=nginx
export APP=app
export DB=mariadb
export NODE=gulp
export TYPE=php
export USER_ID=$(id -u)

$PWD/bin/launcher/launcher.sh "$@"
