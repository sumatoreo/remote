FROM php:7.4-fpm

# Описание про MSSQL драйвер https://laravel-news.com/install-microsoft-sql-drivers-php-7-docker
# Arguments defined in docker-compose.yml
# ARG USER
# ARG UID

RUN apt-get update; \
    apt-get upgrade -y; \
    apt-get -y install apt-utils lsb-release apt-transport-https ca-certificates wget gnupg2 dirmngr; \
	wget --quiet -O - https://packages.microsoft.com/keys/microsoft.asc | apt-key add -; \
    echo "deb [arch=amd64] https://packages.microsoft.com/debian/`lsb_release -rs`/prod `lsb_release -sc` main" | tee /etc/apt/sources.list.d/mssql-release.list; \
    apt-get update; \
	ACCEPT_EULA=Y apt-get install -y --allow-downgrades --allow-remove-essential --allow-change-held-packages \
        unixodbc-dev \
        msodbcsql17 \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        openssl \
       	imagemagick \
    	libmagickwand-dev \
        libzip-dev \
        zip \
    	unzip \
    	libxml2-dev \
        nodejs \
        npm \
        curl; \
    sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf; \
    sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j$(nproc) \
    	gd \
    	imap \
    	mbstring \
    	openssl \
    	curl; \
    docker-php-source delete; \
    rm -rf /var/lib/apt/lists/*

# Development php.ini
RUN if [ ${DEVELOP} = true ]; then \
        mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    else \
        mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    fi

# Zip
RUN docker-php-ext-install zip

# Redis
RUN pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis

# ImageMagick
RUN pecl install imagick; \
    docker-php-ext-enable imagick

# XML
RUN docker-php-ext-install xml

# MySQL & MSSQL
RUN docker-php-ext-install pdo pdo_mysql soap; \
    pecl install sqlsrv pdo_sqlsrv; \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

# Tokenizer
RUN docker-php-ext-install tokenizer

# OpCache
RUN docker-php-ext-install opcache

# Exif
RUN docker-php-ext-install exif; \
    docker-php-ext-enable exif

# xDebug
# RUN pecl install xdebug; \
#     docker-php-ext-enable xdebug

# Xhprof
# RUN curl -L -o /tmp/xhprof.tar.gz "https://github.com/tideways/php-xhprof-extension/archive/v5.0.1.tar.gz"; \
#     mkdir -p xhprof; \
#     tar -C xhprof -zxvf /tmp/xhprof.tar.gz --strip 1; \
#     ( \
#         cd xhprof \
#         && phpize \
#         && ./configure \
#         && make \
#         && make install \
#     ); \
#     rm -r xhprof; \
#     rm /tmp/xhprof.tar.gz

# MongoDB
RUN pecl install mongodb; \
    docker-php-ext-enable mongodb


# Install apidoc
RUN npm install apidoc -g

COPY ./conf.d $PHP_INI_DIR/conf.d

# Create system user to run Artisan Commands
# RUN useradd -G www-data,root -u $UID -d /home/$USER $USER
#     chown -R $USER:$USER /home/$USER

WORKDIR /var/www/html

# USER $USER

EXPOSE 9000	

CMD ["php-fpm"]