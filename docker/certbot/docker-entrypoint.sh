#!/bin/sh
set -e

mkdir -p $HOME/ssl/private
mkdir -p $HOME/ssl/certs

echo "Generating certificate for react"
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout $HOME/ssl/private/koupon.key -out $HOME/ssl/certs/koupon.crt -subj '/CN=dev.salokain.com'
