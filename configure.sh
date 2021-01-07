#!/bin/bash

# Settings up your own bridge
docker network create \
	--driver=bridge \
	--subnet=10.10.0.0/16 \
	--gateway=10.10.0.1 \
	--ip-range=10.10.88.0/24 \
	br0

# Create or Update nginx certificates 
openssl dhparam \
	-out nginx/ssl/certs/dhparam.pem 2048

openssl req -x509 -nodes\
	-days 365 \
	-newkey rsa:2048 \
	-keyout nginx/ssl/private/nginx-selfsigned.key \
	-out nginx/ssl/certs/nginx-selfsigned.crt \
	-config nginx/ssl/req.cnf