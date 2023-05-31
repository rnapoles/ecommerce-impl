# ecommerce-impl in PHP with Symfony 6.2

## Run project in dev mode

* install PHP 8.1
* [install Composer](https://getcomposer.org/doc/00-intro.md)
* [install Symfony CLI](https://symfony.com/download)
* install Mysql, Postgresql or Sqlite
* [install Rabbitmq](https://www.rabbitmq.com/download.html)
* [install Meilisearch](https://www.meilisearch.com/docs/learn/getting_started/installation)
* [install Redis](https://redis.io/docs/getting-started/installation/)

## On Debian | Ubuntu

* install require php extensions

```sh
apt install php8.1-{cli,common,amqp,ast,bcmath,bz2,calendar,ctype,decimal,dev,dom,exif,ffi,fileinfo,gd,gettext,gmagick,http,iconv,imagick,intl,json,ldap,mbstring,mcrypt,memcache,memcached,mongodb,mysql,mysqli,mysqlnd,opcache,pdo,pdo-mysql,pdo-pgsql,pdo-sqlite,phar,posix,redis,simplexml,sockets,sqlite3,tidy,tokenizer,uuid,xmlreader,xmlwriter,xsl,yaml,zip}
```

## Windows
* Todo

## Docker
* Todo

## Run Project

```
cd php-symfony-backend
```

Edit .env:

```
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
SEARCH_ENGINE_DSN=http://127.0.0.1:7700
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
```

```
symfony check:requirements
symfony server:ca:install
composer update
php bin\console doctrine:schema:update --force
php bin\console app:setup-search-engine
php bin\console app:populate-users
php bin\console app:populate-categories
php bin\console app:populate-products
php bin\console app:index-products
symfony serve --port=12000
```

## Test endpoints

* To see endpoints:
```sh
php bin\console debug:router
```

* Run tests:
```sh
cd nodejs-integration-tests
yarn
yarn start
```

## Todo
* implement a CRUD for Category entity
* implement a CRUD for Group entity
* finalize the CRUD implementation of User entity
* add swagger-ui to project
* use a message queue when create, update or delete a Product to index task in another process 
* implement a recomendation engine to suggest products to user
* implement a prediction engine for sales
* use redis as cache for some statistics