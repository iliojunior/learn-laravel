# Learn Laravel

> Project for learning Laravel Framework v5.5 using TDD

## Requirements

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Build

``` bash
# run docker-compose with mysql, php, apache
# Running in http://localhost:8081 
docker-compose up -d
```
## Unit Test

``` bash
# Run in docker
docker-compose exec app vendor/bin/phpunit
```