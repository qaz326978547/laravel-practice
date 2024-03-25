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
![截圖 2024-03-25 下午11 09 50](https://github.com/AlanSyue/laravel-practice/assets/33183531/9fc9ba2a-8431-4537-83dd-866f8221543d)


## Access to the website.
Clik the [link](http://localhost:9001/) and you will see the screen like below:
<img width="1440" alt="截圖 2024-03-25 下午11 16 43" src="https://github.com/AlanSyue/laravel-practice/assets/33183531/e37b59a6-c245-4617-884e-397244032231">
