#!/bin/bash

#########################
# NIWEE DOCKER LAUNCHER #
#########################

# Set script name
if [ -f "./php" ]; then
    if [ -d "$HOME/.niwee" ]; then
        script_name="php"
    else
        script_name="./php"
    fi
elif [ -f "./wp" ]; then
    if [ -d "$HOME/.niwee" ]; then
        script_name="wp"
    else
        script_name="./wp"
    fi
elif [ -f "./symfo" ]; then
    if [ -d "$HOME/.niwee" ]; then
        script_name="symfo"
    else
        script_name="./symfo"
    fi
else
    script_name="$0"
fi

APP_DIR=$(pwd)

###
# Set colors variables
###
RED=$'\e[31m'
GREEN=$'\e[32m'
YELLOW=$'\e[33m'
CYAN=$'\e[36m'
NC=$'\e[0m'

if [ -f "docker-compose.override.yml" ]; then
    launcherComposeFile="-f docker-compose.yml -f docker-compose.override.yml"
else
    launcherComposeFile="-f docker-compose.yml"
fi

###
# Create an error message
###
launcherErrorMessage() {
    if [[ $2 != "--no-help" ]]; then
        echo >&2 "[${GREEN}LNC${NC}]::[${RED}ERROR${NC}] $1 See '${YELLOW}${script_name} help${NC}'."
    else
        echo >&2 "[${GREEN}LNC${NC}]::[${RED}ERROR${NC}] $1"
    fi
}

###
# Check that docker executables are available and that docker is started
###
# docker
if ! [[ -x $(command -v docker) ]]; then
    launcherErrorMessage '`docker` executable is unavailable.' --no-help
    exit 1
fi

# docker-compose
if ! [[ $(command -v docker-compose) || $(command -v docker compose) ]]; then
    launcherErrorMessage '`docker-compose` executable is unavailable.' --no-help
    exit 1
fi

if [[ $(docker network ls -q 2>/dev/null) == '' ]]; then
    launcherErrorMessage '`docker` is not started.' --no-help
    exit 1
fi

###
# Get absolute path of this script
###
getAbsolutePath() {

    # Résolution des liens
    local PRG="$0"
    while [ -h "$PRG" ]; do
        local ls=$(ls -ld "$PRG")
        local link=$(expr "$ls" : '.*-> \(.*\)$')
        if expr "$link" : '/.*' >/dev/null; then
            local PRG="$link"
        else
            local PRG=$(dirname "$PRG")/"$link"
        fi
    done

    local PRGDIR=$(dirname "$PRG")
    PRGDIR=$(
        cd "$PRGDIR"
        pwd
    )
    local returnPath="$1"
    eval "$returnPath=\$PRGDIR"
}

getAbsolutePath LAUNCHER_LOCATION

###
# Set command related variables
###
if [[ $(command -v docker compose) ]]; then
    DC="docker compose"
fi

if [[ $(command -v docker-compose) ]]; then
    DC="docker-compose"
fi

###
# Source the configuration files
###
[ -f ".env" ] && source .env
[ -f ".db.env" ] && source .db.env
[ -f ".db.local.env" ] && source .db.local.env
[ -f "$APP_DIR.env" ] && source $APP_DIR.env

###
# Set container related variables
###
APP_EXEC="${DC} $launcherComposeFile exec $APP sh -c"
DB_EXEC="${DC} $launcherComposeFile exec $DB sh -c"
NODE_EXEC="${DC} $launcherComposeFile exec $NODE sh -c"
SSH_EXEC="ssh $SSH_USER@$SSH_HOST"
DOCKCOMPOSE="${DC} -f ${DIR}docker-compose.yml"

###
# Tools
###
DATETIME=$(date '+%Y-%m-%d-%H-%M-%S')
DATE=$(date '+%d-%m-%Y')
DATE_PRETTY=$(date '+%d\/%m\/%Y')
TIME=$(date '+%H-%M-%S')
TIME_PRETTY=$(date '+%H:%M:%S')

###
# Set folder related variables
###
SQL_FOLDER="./$APP_DIRsql"

###
# Set git related variables
###
GIT_APP="git -C $APP_DIR"
GIT_PROD="git -C /var/prod/app"
GIT_IMAGE="git -C image/"

###
# Set project name
###
if [ -d $APP_DIR/.git ]; then
    PROJECT_NAME=$(basename -s .git $(git -C $APP_DIR config --get remote.origin.url) | sed 's/.*/&/')
else
    PROJECT_NAME=${APP_DIR##*/}
fi

###
# Set ask to() prompt user when needed
###
ask() {
    local prompt default reply
    if [ "${2:-}" = "Y" ]; then
        prompt="Y/n"
        default=Y
    elif [ "${2:-}" = "N" ]; then
        prompt="y/N"
        default=N
    else
        prompt="y/n"
        default=
    fi
    while true; do
        echo -ne "[${GREEN}LNC${NC}]::[${CYAN}Prompt${NC}] $1 [$prompt] "
        read reply </dev/tty
        if [ -z "$reply" ]; then
            reply=$default
        fi
        case "$reply" in
        Y* | y*) return 0 ;;
        N* | n*) return 1 ;;
        esac
    done
}

###
# Create an invalid option message
###
launcherInvalidoption() {
    echo "[${GREEN}LNC${NC}] '${CYAN}$1${NC}' is not a valid option for the command '${CYAN}$2${NC}'. See '${YELLOW}${script_name} help${NC}'."
}

###
# Create an invalid command message
###
launcherInvalidcommand() {
    echo "[${GREEN}LNC${NC}] '${CYAN}$1${NC}' is not a valid command. See '${YELLOW}${script_name} help${NC}'."
}

###
# Create a nice message
###
launcherMessage() {
    if [[ $2 == "--sameline" ]]; then
        echo -ne "[${GREEN}LNC${NC}] $1 ${CYAN}=>${NC} "
    elif [[ $2 == "--prompt" ]]; then
        read -p "$(echo "[${GREEN}LNC${NC}] $1 ${GREEN}:${CYAN} ")" $4
    else
        echo "[${GREEN}LNC${NC}] $1"
    fi
}

launcherok() {
    echo " [(${GREEN})OK${NC}]"
}

launcherfail() {
    echo " [${RED}FAILED${NC}]"
}

###
# Check for variables
###
[ -z "$SERVER" ] && launcherErrorMessage '$SERVER is not defined !' --no-help && exit
[ -z "$APP" ] && launcherErrorMessage '$APP is not defined !' --no-help && exit
[ -z "$DB" ] && launcherErrorMessage '$DB is not defined !' --no-help && exit
[ -z "$NODE" ] && launcherErrorMessage '$NODE is not defined !' --no-help && exit
[ -z "$TYPE" ] && launcherErrorMessage '$TYPE is not defined !' --no-help && exit
[ -z "$SQL_FILE_PATH" ] && launcherErrorMessage '$SQL_FILE_PATH is not defined !' --no-help && exit

launcherCheckComposeFile() {
    if $DC $launcherComposeFile config &>/dev/null; then
        return 0
    else
        launcherErrorMessage "Compose file seems to be invalid !"
        $DC $launcherComposefile config
        return 1
    fi
}

launcherCheckComposeFile || exit

###
# Check if the project is running
###
launcherIsRunning() {
    if [[ $($DC $launcherComposeFile top) != "" ]]; then
        return 0
    else
        launcherErrorMessage "Containers are not up."
        if ! ask "Do you want to continue ?"; then
            return 1
        fi
    fi
}

launcherIsNotRunning() {
    if [[ $($DC $launcherComposeFile top) != "" ]]; then
        launcherErrorMessage "Containers are already up."
        return 1
    else
        return 0
    fi
}

##@conflicts: Reset folders to the last commit
launcherConflicts() {
    git fetch --all
    git reset --hard origin/main
    git -C $APP_DIR fetch --all
    git -C $APP_DIR reset --hard origin/main
}

###
##@url: Get current url
###
launcherUrl() {
    launcherIsRunning || exit
    if [ -z "$1" ]; then
        launcherMessage "Getting current URL"
        launcherMessage "Current URL is ${GREEN}$($APP_EXEC ' --allow-root option get siteurl')${NC}"
    else
        urlcommand="$1"
        case $urlcommand in

        prod) launcherUrlProd $2 $3 ;;

        local) launcherUrlLocal $2 $3 ;;

        *) launcherInvalidcommand $urlcommand ;;
        esac
    fi
}

###
##@url-prod: Set Url to production
###
launcherUrlProd() {
    launcherIsRunning || exit
    launcherMessage "Changing URL to prod"
    if $APP_EXEC " search-replace $LOCAL_URL $PROD_URL --all-tables --quiet"; then
        launcherMessage "Changed to prod url"
    fi
}

###
##@url-local: Set Url to local
###
launcherUrlLocal() {
    launcherIsRunning || exit
    launcherMessage "Changing URL to local"
    if $APP_EXEC " search-replace ${PROD_URL} ${LOCAL_URL} --all-tables --quiet"; then
        launcherMessage "Changed to local url"
    fi
}

launcherASCII() {
    echo -n "
██╗░░░░░░█████╗░██╗░░░██╗███╗░░██╗░█████╗░██╗░░██╗███████╗██████╗░
██║░░░░░██╔══██╗██║░░░██║████╗░██║██╔══██╗██║░░██║██╔════╝██╔══██╗
██║░░░░░███████║██║░░░██║██╔██╗██║██║░░╚═╝███████║█████╗░░██████╔╝
██║░░░░░██╔══██║██║░░░██║██║╚████║██║░░██╗██╔══██║██╔══╝░░██╔══██╗
███████╗██║░░██║╚██████╔╝██║░╚███║╚█████╔╝██║░░██║███████╗██║░░██║
╚══════╝╚═╝░░╚═╝░╚═════╝░╚═╝░░╚══╝░╚════╝░╚═╝░░╚═╝╚══════╝╚═╝░░╚═╝                                                      
"
    echo -n '                                              by '
    echo $(echo -n 'NiWee Productions' | "${LAUNCHER_LOCATION}"/rainbow.awk) "running" $(echo $(basename ${APP_DIR}) | "${LAUNCHER_LOCATION}"/rainbow.awk).
}

##@help: Display help
launcherHelp() {
    # Print launcher ascII
    launcherASCII

    # Remove help if it exists
    [ -f "help" ] && rm help

    # Print how to use
    echo -e "${CYAN}Usage${NC}: ${script_name} [${GREEN}options${NC}(] [${YELLOW}Default: Print ASCII${NC}]"

    # Get all lines starting with comment pattern
    grep "##@" $0 | cut -c 4- >>help

    # Reverse sort file to put all descending children under parent
    sort -r help >help.tmp && mv help.tmp help

    ###
    # Separate parent-child commands and replace parent- by tab
    ###
    while IFS="" read -r p || [ -n "$p" ]; do
        if [[ $p == *-* ]]; then
            sed -i 's/^.*-/    ==> /' help
        fi
    done <help

    # Remove >>help & empty lines from help file
    sed -i "s/>>help//g" help
    sed -i '$ d' help

    ###
    # Set color coding
    ###
    sed -i "s|^|\\${CYAN}|" help
    sed -i "s|:|\\${NC}:|" help
    while IFS="" read -r p || [ -n "$p" ]; do
        if [[ $p != *"==>"* ]]; then
            printf "\n$p\n"
        else
            printf "$p\n"
        fi
    done <help
    echo

    [ -f "help" ] && rm help
    exit 1
}

##@build: Build the docker containers
launcherBuild() {
    launcherMessage "Building images."
    if $DC $launcherComposeFile build; then
        return 0
    else
        return 1
    fi
}

##@pull: Pull the docker images
launcherPull() {
    launcherMessage "Pulling images."
    if $DC $launcherComposeFile pull; then
        return 0
    else
        launcherErrorMessage "Could not pull."
        return 1
    fi
}

##@up: Launch the docker containers
launcherUp() {
    launcherMessage "Starting containers."
    if [[ $1 == "-d" ]]; then
        if $DC $launcherComposeFile up; then
            return 0
        else
            return 1
        fi
    else
        if $DC $launcherComposeFile up; then
            return 0
        else
            return 1
        fi
    fi
}

##@logs: Open compose logs
launcherLogs() {
    launcherIsRunning || exit
    launcherMessage "Opening logs."
    $DC $launcherComposeFile logs -f --tail=100 "$@"
}

##@start: Start the project
launcherStart() {
    if [[ $1 == "-d" ]]; then
        launcherPull && launcherUp -d
    else
        launcherPull && launcherUp
    fi
}

##@kill: Backup and down all the containers & volumes
launcherKill() {
    if [ -z "$1" ]; then
        if ask "Are you sure you want to$RED kill the containers $NC ? \nThis will permanently delete all fictive volumes ($RED including databases$NC )."; then
            Stop
            launcherMessage "Killing containers."
            if $DC $launcherComposeFile down -v; then
                return 0
            else
                return 1
            fi
        else
            return 1
        fi
    elif [ $1 == "-y" ]; then
        Stop
        launcherMessage "Killing containers."
        $DC $launcherComposeFile down -v
        return 0
    else
        return 1
    fi
}

##@stop: Stop the containers
launcherStop() {
    if launcherDbDump; then
        launcherMessage "Stopping containers."
        $DC $launcherComposeFile stop
        if [[ $1 == "-k" || $1 == "--kill" ]]; then
            Kill
        fi
    fi
}

##@restart: Restart the containers
launcherRestart() {
    launcherCheckComposeFile

    launcherMessage "Restarting containers."
    $DC $launcherComposeFile restart
}

##@update: Pull all git repositories
launcherUpdate() {
    launcherMessage "Updating launcher."
    git -C $LAUNCHER_PATH pull
}

##@rebuild: Backup, down all containers & volumes then reup everything
launcherRebuild() {
    launcherMessage "Rebuilding project."
    [ $TYPE == "wp" ] && $APP_EXEC "wp db export /var/opt/$APP_DIR/sql/rebuild.sql"
    if sudo ss -tulpn | grep LISTEN | grep -q :443; then
        if launcherKill --proxy; then
            Pull --proxy
            Up --proxy
            $APP_EXEC " db import /var/opt/$APP_DIRsql/rebuild.sql"
            Logs --proxy
        else
            launcherMessage "Aborted the rebuild."
        fi
    fi
    if launcherKill; then
        Pull
        Up
        $APP_EXEC " db import /var/opt/$APP_DIR/sql/rebuild.sql"
        Logs
    else
        launcherMessage "Aborted the rebuild."
    fi
}

##@git: Add, commit, pull & push the project to git
launcherGit() {
    # If this folder contains a git repo
    echo $APP_DIR
    if [ -d "$APP_DIR/.git" ]; then
        launcherMessage "Pushing project to Git."

        # If user just wants to push, then just do that.

        ##@git-push: Just push the project to git
        [[ $1 == "push" ]] && $GIT_APP push -u origin $(git rev-parse --abbrev-ref HEAD) && exit

        # Dump the database
        launcherDbDump -f

        # Add
        if ! $GIT_APP add .; then
            launcherErrorMessage "Could not add to Git !"
            exit
        fi

        # Handle commit options
        if [[ -z "$1" || $1 == "commit" ]]; then
            # Classic, ask for commit message in default editor
            if ! $GIT_APP commit -a; then
                launcherErrorMessage "Could not commit !"
                exit
            fi
        elif [[ $1 == "-s" || $1 == "--save" ]]; then
            # Commit saying "Just saving."
            if ! $GIT_APP commit -m "Just saving."; then
                launcherErrorMessage "Could not commit !"
                exit
            fi
        elif [[ $1 == "-sh" || $1 == "--shutdown" ]]; then
            # Commit saying "Shutting down."
            if ! $GIT_APP commit -m "Shutting down."; then
                launcherErrorMessage "Could not commit !"
                exit
            fi
        fi

        # Stop here if use just wants to commit

        ##@git-commit: Just add & commit the project
        if [[ $1 != "commit" ]]; then
            if ! $GIT_APP pull -q; then
                # Pull to avoid big conflicts
                launcherErrorMessage "Could not pull from Git !"
                exit
            fi

            if ! $GIT_APP push -u origin $(git rev-parse --abbrev-ref HEAD); then
                # And finally, push
                launcherErrorMessage "Could not push to Git !"
                exit
            fi
        fi
    else
        launcherErrorMessage "This application does not have a git repository !"
    fi
}

###
##@save: Stop and save the project quickly
###
launcherSave() {
    launcherMessage "Saving project."
    launcherDbDump
    launcherStop
    launcherGit --save
}

##@cli: Open a CLI in the main app container
launcherCli() {
    if launcherIsRunning; then
        launcherMessage "Opening shell in $APP container."
        $DC $launcherComposeFile exec $APP bash
    else
        launcherMessage "Project is not up, nothing to kill."
        return 1
    fi
}

##@clone: Clone a repo in the app folder
launcherClone() {
    read -p "Repository to clone: " repo
    if [ -d "$APP_DIR" ]; then
        if ask "There is already an app folder, remove it ?"; then
            rm -r $APP_DIR
        fi
    fi
    git clone $repo app
}

##@project: Take actions upon the current project
launcherProject() {
    if [ -z "$1" ]; then
        if [ -d "$APP_DIR" ]; then
            launcherMessage "Project is $PROJECT_NAME"
        else
            launcherErrorMessage "You have not yet instanciated a project in this infrastructure"
        fi
    elif [ $1 == "new" ]; then
        launcherProjectNew
    elif [ $1 == "template" ]; then
        launcherProjectTemplate
    fi
}

###
# Clone the image in the image dir
###
launcherImageInstanciate() {
    if [ ! -d "image/" ]; then
        launcherMessage "Instanciating docker image."
        git clone git@gitlab.com:niwee-productions/docker-images/PHP.git image
        if [[ ! -f override.yml && -f image/override.yml ]]; then
            cp image/override.yml .
        fi
    else
        launcherErrorMessage "This project already contains an 'image' folder !"
    fi
}

##@image-build: Build the image
launcherImageBuild() {
    launcherMessage "Building docker image."
    if [ -z "$1" ]; then
        docker build -t niwee/PHP:latest ./image
    elif [ $1 == "--dev" ]; then
        docker build -t niwee/PHP:dev ./image
    fi
}

##@image-push: Push the image to Docker Hub
launcherImagePush() {
    if phpImageBuild; then
        launcherMessage "Pushing docker image to Docker Hub."
        if [ -z "$1" ]; then
            docker push niwee/PHP:latest
        elif [ $1 == "--dev" ]; then
            docker push niwee/PHP:dev
        fi
    else
        launcherErrorMessage "Could not build php image !"
    fi
}

##@image-git: Push the image to its Git repository
launcherImageGit() {
    launcherMessage "Pushing image files to Git."
    $GIT_IMAGE add .
    $GIT_IMAGE commit -a
    $GIT_IMAGE pull -q
    $GIT_IMAGE push
}

##@image-test: Test deploy the current image
launcherImageTest() {
    launcherMessage "Testing the current image."
    $DC down -v
    $DC build
    $DC up -d
    $DC logs -f PHP
}

##@image: Develop the current infrastructure image (default: instanciate)
launcherImage() {
    if [ -z "$1" ]; then
        launcherImageInstanciate
    else
        if [ -d "image/" ]; then
            launcherimagecommand="$1"
            case $launcherimagecommand in

            build) launcherImageBuild ;;

            push) launcherImagePush ;;

            git) launcherImageGit ;;

            test) launcherImageTest ;;

            *) launcherInvalidcommand $launcherimagecommand ;;
            esac
        else
            if ask "Could not find the $script_name image in this infrastructure, instanciate ?"; then
                launcherImageInstanciate
            fi
        fi
    fi
}

##@prod-init: Initialize production on the server
launcherProdInit() {
    launcherMessage "Initializing production."
    $APP_EXEC "prod init"
}

##@prod-pull: Get production data
launcherProdPull() {
    launcherMessage "Pulling production."
    $APP_EXEC "prod pull"
}

##@prod-push: Push project to production
launcherProdPush() {
    launcherMessage "Pushing production."
    $APP_EXEC "prod push"
}

##@prod-connect: Connect to the production server
launcherProdConnect() {
    launcherMessage "Connecting to production server."
    $SSH_EXEC
}

###
##@prod: Take actions upon production
###
launcherProd() {
    if [[ $TYPE == "wp" ]]; then
        if [ -z "$1" ]; then
            if [[ -z "$PROD_MODE" || $PROD_MODE == "" ]]; then
                launcherMessage "Production mode is set to 'classic' (default)"
            else
                launcherMessage "Production mode is set to $PROD_MODE"
            fi
        else
            if launcherIsRunning; then
                launcherProdCommand="$1"
                case $launcherProdCommand in

                init) launcherProdInit ;;

                push) launcherProdPush $1 $2 ;;

                pull) launcherProdPull $1 $2 ;;

                connect) launcherProdConnect $1 $2 ;;

                *) launcherInvalidcommand $launcherProdCommand ;;
                esac
            fi
        fi
    else
        launcherErrorMessage "Sorry, prod commands are not yet available for the $script_name infrastructure." --no-help
    fi
}

##@db-check: Check the database tables
launcherDbCheck() {
    if [[ $TYPE == "wp" ]]; then
        launcherMessage "Checking database."
        $APP_EXEC "wp db check"
    else
        launcherErrorMessage "This command is only available for Wordpress (for now), sorry." --no-help
    fi
}

##@db-import: Import an SQL file
launcherDbImport() {
    if [ -z "$2" ]; then
        launcherMessage "Importing database."
        if [[ $TYPE == "wp" ]]; then
            $APP_EXEC "wp db import $SQL_FILE_PATH/dump.sql"
        else
            $DC $launcherComposeFile exec -T $DB sh -c "mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE" <$APP_DIR/$SQL_FILE_PATH/dump.sql
        fi
    fi
}

##@db-dump: Exoport to an SQL file
launcherDbDump() {
    launcherMessage "Exporting database." --sameline
    ${DB_EXEC} "mysqldump -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD --databases $MYSQL_DATABASE" >$APP_DIR/$SQL_FILE_PATH/dump.sql
    if [ $? -eq 0 ]; then
        sed -i "1s/^/-- DUMPED ON $DATE_PRETTY at $TIME_PRETTY\n\n/" "$APP_DIR/$SQL_FILE_PATH/dump.sql"
        echo -e "${GREEN}Sucess !$NC Exported to ./$SQL_FILE_PATH/dump.sql"
    else
        # Dump fail
        launcherfail
        launcherErrorMessage "Could not export database."
    fi
}

##@db-cli: Open a shell in the DB container
launcherDbCli() {
    launcherMessage "Entering $DB cli."
    if [[ $TYPE == "wp" ]]; then
        $APP_EXEC "wp db cli"
    else
        ${DB_EXEC} "mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE"
    fi
}

##@db-optimize: Optimize the tables
launcherDbOptimize() {
    if [[ $TYPE == "wp" ]]; then
        launcherMessage "Optimizing database."
        $APP_EXEC "wp db optimize"
    else
        launcherErrorMessage "This command is only available for Wordpress (for now), sorry." --no-help
    fi
}

##@db-clean: Delete the database
launcherDbClean() {
    # Ask user before doing the dirty deed
    if ask "Are you sure you want to$RED delete the database$NC ? \nThis action is irreversible !"; then
        launcherMessage "Deleting database" --sameline

        # Create a delete file and import it
        touch ./sql/reset.sql
        echo -e "DROP DATABASE $MYSQL_DATABASE;\nCREATE DATABASE $MYSQL_DATABASE;" >>./sql/reset.sql
        if $DC $launcherComposeFile exec -T $DB sh -c "mysql -u '$MYSQL_USER' -p'$MYSQL_PASSWORD' '$MYSQL_DATABASE'" <./sql/reset.sql; then
            launcherok
        else
            launcherfail
            launcherErrorMessage "Could not delete database."
        fi
        rm ./sql/reset.sql
    fi
}

##@db: Take actions upon the database
launcherDb() {
    # If project is not running, exit
    launcherIsRunning || exit

    if [ -z "$1" ]; then
        launcherDbCheck
    else
        phpdbcommand="$1"
        case $phpdbcommand in

        check) launcherDbCheck ;;

        import) launcherDbImport $1 $2 ;;

        dump) launcherDbDump $1 $2 ;;

        cli) launcherDbCli ;;

        optimize) launcherDbOptimize ;;

        clean) launcherDbClean ;;

        *) launcherInvalidoption $phpdbcommand "db" ;;
        esac
    fi
}

##@composer: Use composer inside the container
launcherComposer() {
    $DC exec app sh -c "composer $2 $3 $4 $5 $6 $7 $8"
}

# Read Commands
case $1 in

'') launcherASCII ;;

help | --help | -h) launcherHelp $2 $3 $4 $5 $6 $7 $8 ;;

start) launcherStart $2 $3 $4 $5 $6 $7 $8 ;;

up) launcherUp $2 $3 $4 $5 $6 $7 $8 ;;

build) launcherBuild $2 $3 $4 $5 $6 $7 $8 ;;

stop) launcherStop $2 $3 $4 $5 $6 $7 $8 ;;

pull) launcherPull $2 $3 $4 $5 $6 $7 $8 ;;

kill) launcherKill $2 $3 $4 $5 $6 $7 $8 ;;

restart) launcherRestart $2 $3 $4 $5 $6 $7 $8 ;;

rebuild) launcherRebuild $2 $3 $4 $5 $6 $7 $8 ;;

update) launcherUpdate $2 $3 $4 $5 $6 $7 $8 ;;

save) launcherSave $2 $3 $4 $5 $6 $7 $8 ;;

cli) launcherCli $2 $3 $4 $5 $6 $7 $8 ;;

code) launcherNotificationOpenProjectInVscode $2 $3 $4 $5 $6 $7 $8 ;;

clone) launcherClone $2 $3 $4 $5 $6 $7 $8 ;;

launcherASCII) launcherASCII $2 $3 $4 $5 $6 $7 $8 ;;

project) launcherProject $2 $3 $4 $5 $6 $7 $8 ;;

image) launcherImage $2 $3 $4 $5 $6 $7 $8 ;;

conflict) launcherConflicts $2 $3 $4 $5 $6 $7 $8 ;;

db) launcherDb $2 $3 $4 $5 $6 $7 $8 ;;

prod) launcherProd $2 $3 $4 $5 $6 $7 $8 ;;

url) launcherUrl $2 $3 $4 $5 $6 $7 $8 ;;

logs) launcherLogs $2 $3 $4 $5 $6 $7 $8 ;;

git) launcherGit $2 $3 $4 $5 $6 $7 $8 ;;

composer) launcherComposer $1 $2 $3 $4 $5 $6 ;;

*) launcherInvalidcommand $1 && exit 0 ;;
esac
