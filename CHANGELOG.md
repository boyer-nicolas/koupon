CHANGELOG
----------------------

[2023-03-23]

[2023-02-15]
 * 🪚 refactor(#env): Added host & port + renamed template file
 * 💡feat(#database): Switched to linuxserver mariadb

[2023-01-20]
 * removed override.yml
 * 🔨 fix(#Override): Fixed compose override file name
 * 🔨 fix(#DB): Fixed db name generation & pma update

[2023-01-13]
 * 🔨 fix(#PMA): Added username genrationt
 * 💡feat(#Reusable): Can now reuse configs
 * 🔨 fix(#Installer): Fixed server path (for drupal)
 * 🪚 refactor(#override): Removed main override file
 * 🪚 refactor(#sensible): Removed sensible data
 * 💡feat(#Generator): Added env generator written in php

[2023-01-12]
 * 🔨 fix(#policies): Fixed depends policies to launch faster
 * bugfix(#dbdump) Added escape possibility when automating
 * Added ssh config to container so we can git

[2022-12-08]

[2022-12-07]
 * feat(#Yarn): Added yarn dev to quickly start projects
 * feat(#Firebase): Implemented sdk
 * feat(#Hero): Made animations

[2022-11-16]
 * fix(#Caddy): Fixed url policy to env
 * refactor(#Registry): Switched to our registry

[2022-10-25]
 * feat(#Commands): Bin folder restored
 * docs(#Refactor readme): Readme now adapted for new archi
 * fix(#DB): Db commands now work again

[2022-05-25]
 * feat(#Ready): Infrastructure ready to work with any basic project, features to come.
 * refactor(#Infra/app): Went back to the Infrastructure/Application model as it is more UX friendly
 * Readded caddy in docker compose

[2022-05-13]
 * Removed node and composer from initial script

[2022-05-12]
 * Just Saving my work.

[2022-04-27]
 * Just Saving my work.

[2022-04-11]
 * Set

[2022-04-05]
 * Compose
 * Can
 * Added

[2022-03-31]
 * Fixed

[2022-03-26]

[2022-03-18]
 * Just Saving my work.
 * [FEATURE] ==> Added the option for php version
 * [FIX] ==> Assets installation
 * Update README.md

[2022-03-08]
 * Just Saving my work.
 * Just Saving my work.

[2022-03-07]

[2022-03-04]
 * [FIX] ==> Container names, removed sql folder from auto gitignore
 * [FEATURE] ==> Improved auto gitignore
 * [FEATURE] ==> Added auto gitignore
 * Just Saving my work.
 * [FIX] ==> Restart [FEATURE] ==> Added db persistence

[2022-03-03]
 * Just Saving my work.

[2022-02-22]
 * Update cron/scripts/dump.sh
 * [REFACTOR] ==> Switched from PROJECt_NAME to pname
 * [FEATURE] ==> Switched COMPOSE_PROJECT_NAME to PROJECT_NAMe
 * [feat]: Add .idea and api folder to gitignore

[2022-02-21]
 * [FEATURE] ==> Now runs on proxy [ROADMAP] ==> Add option to run without proxy if not detected

[2022-01-30]
 * Just Saving my work.

[2022-01-29]
 * [FEATURE] ==> Added cron runner dumping the database every 5 minutes

[2022-01-26]

[2022-01-25]
 * [FEATURE] ==> Add support for extra.sh at the root of apps
 * Add LICENSE
 * Just Saving my work.
 * [ROLLBACK] ==> Maildev cannot be proxied
 * [COMING SOON] => Maildev on custom uri

[2022-01-21]

[2022-01-20]
 * Update README.md
 * [FIX] ==> Log level set from debug to error
 * [FEATURE] ==> Run.sh auto sets userid
 * [FIX] ==> Now usinng app on app.localhost && pma on pma.localhost with custom image
 * [FEATURE] ==> Added auto update of run.sh file [FEATURE] ==> Added check composer installed before installing
 * [FEATURE] ==> Set compose project name in run.sh
 * [FIX] ==> DB export & import from/to file
 * [FEATURE] ==> Switched to bitnami redis image
 * [FEATURE] ==> Added redis
 * [FIX] ==> Launcher instancaition on new project
 * [FIX] ==> Git clone in readme
 * [FIX] ==> Branch on clone in readme
 * Recreating serve
 * Revert "Merge branch 'serve-folder' into 'main'"
 * Update README.md
 * [README] ==> Changed instructions of getting started to match the new format.

[2022-01-19]
 * [FIX] ==> Compose exec commands working again
 * [FEATURE] ==> Added create run.sh [FEATURE] ==> Added node and composer options in run template
 * [FEATURE] ==> Moving to .serve folder instead of infrastructure/app

[2022-01-17]
 * Fixed UID

[2022-01-13]
 * Feature: Now using dynamic UID depending on niwee package
 * Add .DS_Store to .gitignore

[2021-12-27]
 * Readded logs in pma for compose v2
 * Readded logs in mariadb

[2021-12-17]
 * Set timezone

[2021-12-13]

[2021-12-02]
 * Updated restart policies
 * Added automount of sql dump on mariadb launch

[2021-11-27]
 * Added ports for mariadb

[2021-11-26]
 * Now using niwee caddy image

[2021-11-17]

[2021-11-12]
 * Separated env files for security
 * Added maildev
 * Added phpmyadmin default theme

[2021-11-09]
 * Switched to gitlab docker image

[2021-11-08]
 * save
 * Switched to virtual db peristence
 * Added user for mariadb
 * Replaced mariadb from bitnami with classic mariadb

[2021-11-02]
 * Now using db volume

[2021-10-28]
 * Removed node container

[2021-10-26]
 * Added browsersync ports

[2021-10-25]
 * Fixed coloring in logs
 * Fixed ssl
 * Now running node as user
 * save
 * Modified app path
 * Added versatilyti to node

[2021-10-22]
 * Added caddy property for router

[2021-10-21]

[2021-10-20]
 * Moved back caddyfile
 * Now using caddy

[2021-09-01]

[2021-08-18]
 * Removed php file ext
 * Now using custom conf with new nginx image

[2021-08-17]
 * removed accidental help file
 * added templates array
 * added env vars
 * now passing env vars
 * added proxy mode for nginx

[2021-08-13]
 * added launcher to gitignore
 * removed submodule
 * added launcher as a submodule
 * removed launcher temporarily
 * added launcher
 * save
 * can now run start
 * can now run basic commands

[2021-08-12]
 * added php script

[2021-07-23]
 * fixed niwee config
 * Added db dump full on stop

[2021-07-20]
 * testing pipeline
 * Initial Bitbucket Pipelines configuration

[2021-07-18]
 * removed pipeline file
 * added keys folder to gitignore
 * added pipeline for mirroring
 * Update docker-compose.yml

[2021-07-16]
 * Update docker-compose.yml

[2021-07-06]

[2021-06-29]
 * added letsencrypt volume to avoid cert regeneration

[2021-06-28]
 * fixed proxy depêndenci
 * Should have fixed pma
 * should work while repairing network

[2021-06-26]
 * changed secure entrypoint
 * now using traefik

[2021-06-20]

[2021-06-18]
 * fixed pma
 * removed js volumes, fixed db name
 * no longer need gnome terminal for logs
 * added prod db import
 * removed all

[2021-06-16]
 * remove override

[2021-06-09]
 * removed make status
 * added command to replace string in dump file

[2021-06-06]
 * Update docker-compose.yml
 * Update .env

[2021-05-26]
 * removed unwanted repo
 * add modif

[2021-05-11]

[2021-04-27]
 * changed app name to app from lites
 * removed redis

[2021-04-23]
 * Update '.env'
 * add hostname in dcyml
 * save
 * fixed db commands
 * fixed db host var

[2021-04-22]

[2021-04-21]
 * added redis
 * server root now points to content

[2021-04-20]
 * removed duplicate volumes
 * fixed nginx + perfs

[2021-04-14]
 * gneuuuu
 * fixed tg
 * added wp smtp choucroute
 * fixed image name + env missing in app
 * updated everything to match wp infra as much as possible

[2021-03-25]

[2021-03-05]
 * updated make base
 * upated for using with new nginx image

[2021-03-04]
 * updatedd nginx image name

[2021-02-25]

[2021-02-23]
 * added down command
 * update docker compose

[2021-02-16]
 * removed logs out

[2021-02-09]
 * put official phpmyadmin image back

[2021-02-08]

[2021-02-05]
 * oups
 * double prout
 * prout
 * added env
 * fixed dbdump

[2021-01-25]
 * fixed logs terminal title bar
 * sass now working
 * fixed dbdump
 * fixed dbimport
 * now using lite image with php fpm and nginx proxy

[2021-01-20]
 * makefile maj

[2021-01-19]
 * save/merge
 * now working with php and https
 * Update docker-compose.yml
 * fix start
 * First Commit
