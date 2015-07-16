#!/usr/bin/env bash

docker-machine create --driver virtualbox dev
eval "$(docker-machine env dev)"
docker-compose build
docker-compose up -d
open http://192.168.99.100:8080/
docker exec -it apigility_dev_1 /bin/bash