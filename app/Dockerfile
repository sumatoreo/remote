FROM php:7.4-fpm-buster

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# Repositories and php settings
RUN set -eux; \
	rm /etc/apt/sources.list

COPY ./sources.list /etc/apt/sources.list
COPY ./99fixbadproxy /etc/apt/apt.conf.d/99fixbadproxy
COPY ./conf.d $PHP_INI_DIR/conf.d

# Default PHP Extensions
RUN set -eux; \
	apt-get clean; \
	rm -rf /var/lib/apt/lists/*; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get upgrade -y; \
	apt-get install -y \
		apt \
		libz-dev \
		libjpeg-dev \
		libpng-dev \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libssl-dev \
		libmcrypt-dev \
		libc-client-dev \
		libkrb5-dev \
		libonig-dev \
		lsb-release \
		inetutils-ping \
		apt-transport-https \
		ca-certificates \
		wget \
		dirmngr; \
	rm -rf /var/lib/apt/lists/*

# Development php.ini
RUN if [ ${DEVELOP} = true ]; then \
        mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    else \
        mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    fi

# Curl
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y curl libcurl4-gnutls-dev; \
	rm -rf /var/lib/apt/lists/*; \
	wget -P /etc/ssl/certs https://curl.haxx.se/ca/cacert.pem; \
	sed -i '/;curl.cainfo =/curl.cainfo = /etc/ssl/certs/cacert.pem' /usr/local/etc/php/php.ini

# Microsoft SQL
# https://github.com/microsoft/msphpsql
RUN set -eux; \
	curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -; \
	# Debian Buster
	curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y apt-transport-https gnupg; \
	# optional: for bcp and sqlcmd
	# optional: for unixODBC development headers
	ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools unixodbc-dev; \
	rm -rf /var/lib/apt/lists/*; \
	pecl install sqlsrv pdo_sqlsrv; \
	docker-php-ext-enable sqlsrv pdo_sqlsrv

# GD
RUN set -eux; \
	docker-php-ext-configure gd \
		--with-jpeg \
		--with-freetype; \
	docker-php-ext-install -j$(nproc) gd;

# IMAP
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y libc-client-dev libkrb5-dev; \
	rm -rf /var/lib/apt/lists/*; \
	docker-php-ext-configure imap --with-kerberos --with-imap-ssl; \
    docker-php-ext-install imap

# Zip
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y zlib1g-dev libzip-dev zip; \
    docker-php-ext-install zip; \
    rm -rf /var/lib/apt/lists/*

# Openssl
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y openssl; \
	sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf; \
	sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf; \
	rm -rf /var/lib/apt/lists/*

# Redis
RUN set -eux; \
	pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis

# ImageMagick
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y \
		imagemagick \
		libmagickwand-dev; \
	rm -rf /var/lib/apt/lists/*; \
	pecl install imagick; \
    docker-php-ext-enable imagick

# XML
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y libxml2-dev; \
	rm -rf /var/lib/apt/lists/*; \
	docker-php-ext-install xml

# MySQL & MSSQL
RUN docker-php-ext-install pdo pdo_mysql soap; \
    pecl install sqlsrv pdo_sqlsrv; \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

# MbString
RUN set -eux; \
	docker-php-ext-install mbstring

# Tokenizer
RUN set -eux; \
	docker-php-ext-install tokenizer

# OpCache
RUN set -eux; \
	docker-php-ext-install opcache

# Exif
RUN set -eux; \
	docker-php-ext-install exif; \
    docker-php-ext-enable exif

# Install nodejs and npm
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y nodejs npm; \
	rm -rf /var/lib/apt/lists/*

# Memcached
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y libmemcached-dev; \
	rm -rf /var/lib/apt/lists/*; \
	pecl install memcached; \
	docker-php-ext-enable memcached

# BcMath
RUN set -eux; \
	docker-php-ext-install bcmath

# Ext HTTP install
RUN set -eux; \
	apt-get clean; \
	apt-get update --fix-missing; \
	apt-get install -y libgnutls28-dev; \
	rm -rf /var/lib/apt/lists/*; \
	pecl install raphf propro; \
	docker-php-ext-enable raphf propro; \
	pecl install pecl_http-3.2.2; \
	docker-php-ext-enable http

# Sockets
RUN docker-php-ext-install sockets

# Composer
RUN set -eux; \
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

EXPOSE 9000 

CMD ["php-fpm"]