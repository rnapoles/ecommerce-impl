# ecommerce-impl in PHP with Symfony 6.2

## Run project in dev mode

* install PHP 8.2
* Composer
* Symfony CLI
* install Mysql
* install Rabbitmq
* install Elasticsearch
* install Redis

```
cd symfony-implementation
composer update
php bin\console doctrine:schema:update --force
php bin\console app:setup-users
symfony serve --port=12000
```


### Docker
* Todo