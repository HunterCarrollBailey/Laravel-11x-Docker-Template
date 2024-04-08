# Docker-izing Laravel
-- -
This repo is mainly to hold the template for me to Docker-ize any of my laravel applications.

This has all the required services such as Nginx, MariaDB (MySQL), Redis, PHPMyAdmin, and MailHog. It's easy to expand upon, and should run smoothly.

## Application Setup

1. Remove .gitkeep from /src
2. Clone Your Repo or Create a new Laravel App in /src
3. Setup the ENV; make sure DB_HOST is the name of the database service in docker-compose.yml
4. Run `./local.sh start` from the root directory
5. Once all containers are up run `./local.sh ssh`
6. Once inside the container cd into /src and run `composer install && npm i` to install all of your laravel dependencies and npm packages