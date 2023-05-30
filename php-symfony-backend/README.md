# ecommerce-impl in PHP with Symfony 6.2

## Run project in dev mode

* install PHP 8.2
* install Composer
* install Symfony CLI
* install Mysql
* install Rabbitmq
* install Meilisearch
* install Redis

```
cd php-symfony-backend
composer update
php bin\console doctrine:schema:update --force
php bin\console app:setup-search-engine
php bin\console app:populate-users
php bin\console app:populate-categories
php bin\console app:populate-products
php bin\console app:index-products
symfony serve --port=12000
```

### Docker
* Todo