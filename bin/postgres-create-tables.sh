#!/usr/bin/env bash

docker exec -it $1 psql -h 127.0.0.1 -p 5432 -U admin mydb -f /var/www/html/sql/psql.sql