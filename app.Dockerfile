FROM php:8.5-fpm
RUN apt-get update \
    && apt-get install -y curl zip npm libzip-dev zlib1g-dev unzip libpng-dev libjpeg-dev libfreetype6-dev git mariadb-client libmagickwand-dev openssh-client --no-install-recommends
RUN docker-php-ext-install pdo_mysql zip \
    && pecl install imagick \
    && pecl install xdebug \
    && pecl install redis \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable imagick \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install intl \
    && docker-php-ext-install ftp \
    && docker-php-ext-enable redis \
    && docker-php-ext-install opcache
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# User setup
ARG UID=1000
ARG GID=1000

RUN addgroup --gid $GID appgroup \
    && adduser --uid $UID --gid $GID --disabled-password --gecos "" appuser

# Install qlty as root
RUN curl https://qlty.sh | bash \
 && cp /root/.qlty/bin/qlty /usr/local/bin/qlty

USER appuser
WORKDIR /var/www/html

RUN git config --global --add safe.directory /var/www/html

CMD ["sleep", "infinity"]
