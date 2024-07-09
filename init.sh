#! /bin/bash
# _init starts the project over from scratch purging everything from all volumes,
# and the src directory, creates a new project, rebuilds all containers, performs the db migrations,
# generates the application key, etc. Be WARNED this will purge ALL code you have written and should only be run once.
function _init() {
  rm -rf ./docker/volumes;
  mkdir -p docker/volumes/{pgadmin,pgsql};
  rm -rf ./src;
  mkdir ./src;
  cd ./src || exit;
  composer create-project laravel/laravel . ;
  rm .env .env.example;
  cp ../.env ./;
  cp ../.env.example ./;
  docker compose --env-file .env up -d --build --force-recreate --remove-orphans;
  docker compose --env-file .env exec app composer install;
  docker compose --env-file .env exec app npm install;
  docker compose --env-file .env exec app php artisan migrate:fresh;
  docker compose --env-file .env exec app php artisan key:generate;
  cd ../;
#  git init;
}
# Starts all containers
function _up() {
 docker compose --env-file ./src/.env up -d --watch
}
# Stops all containers
function _stop() {
  docker compose --env-file ./src/.env stop
}
# Tears down all containers
function _down() {
  docker compose --env-file ./src/.env down
}
# Rebuilds all containers
function _rebuild() {
  docker-compose --env-file ./src/.env up -d --build --force-recreate --remove-orphans --watch
}
# Executes the migrations in the src/database/migrations folder on the DB
function _migrate() {
  docker compose --env-file ./src/.env exec app php artisan migrate
}
# Rolls back the last run migrations in chronological order. For each new migrations you must run this command.
function _rollback() {
  docker compose --env-file ./src/.env exec app php artisan migrate:rollback
}
# Drops all tables in db executes the migrations in the src/database/migrations folder on the DB.
# NOTE: You cannot roll this back.
function _refresh() {
  docker compose --env-file ./src/.env exec app php artisan migrate:fresh
}
# Opens up an SSH connection to the App Container
function _ssh() {
  docker compose --env-file ./src/.env exec app bash
}
case $1 in
  "init") _init ;;
  "up") _up ;;
  "stop") _stop ;;
  "down") _down ;;
  "rebuild") _rebuild ;;
  "migrate") _migrate ;;
  "rollback") _rollback ;;
  "refresh") _refresh ;;
  "ssh") _ssh ;;
esac