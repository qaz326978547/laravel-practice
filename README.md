# Laravel Practice

# Requirement
docker and docker-compose: https://www.docker.com/products/docker-desktop

# Getting Started
## Set up the container.
```bash
$ cd docker/
$ cp .env.example .env
$ docker compose up -d
```

## Install the packages.
```bash
$ cd docker/
$ docker compose exec workspace sh
$ cp .env.example .env
$ composer install
$ php artisan key:generate
```

## Access to MySQL database
Mac user can use [Sequel Ace](https://sequel-ace.com/)
You can see the database access information in `docker/.env`



## Access to the website.
Clik the [link](http://localhost:9001/) and you will see the screen like below: