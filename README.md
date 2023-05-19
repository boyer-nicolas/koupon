# Koupon

This is a simple mini-shop like app running with NextJS & PHP. 

## Dependencies
* Composer
* PHP8.2
* NodeJS 18.x
* Docker
* Docker Compose Plugin

## Getting Started
You can simply run `docker compose up` to launch the whole stack, it will launch the API on port 80/443 & the NextJS development system on port 3000.

## Deployment
NextJS's deployment is handled through Vercel with a Github CD. A CI is also implemented and builds + tests the app.

PHP's deployment is handled through docker and is not yet implemented via CD. A CI will be implemented to run PEST V2 tests.

## Development
All requests run through the Router class implemented in the index.php file in the api root folder. They must be ran via the API class in the NextJS app (which runs on Axios).

## Roadmap
[] The coupon must be applied only ten times. Currently the session does not store the applied times correctly.
[] CI for PHP tests.
[] Deployment branches for multiple stages which need to be implemented via Git Tags (UAT, Staging, Production).
