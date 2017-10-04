# Hello-Laravel

## Requirements

- Docker
- Docker Compose

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