FROM php:8.4-fpm
RUN apt-get update \
    && apt-get install -y curl zip npm libzip-dev zlib1g-dev unzip libpng-dev libjpeg-dev libfreetype6-dev git mariadb-client libmagickwand-dev openssh-client --no-install-recommends \
    fcgiwrap \
    && rm -rf /var/lib/apt/lists/*
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
    && docker-php-ext-install opcache \
    && curl -sS https://getcomposer.org/installer \
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

RUN { \
      echo "ping.path = /ping"; \
      echo "ping.response = pong"; \
    } >> /usr/local/etc/php-fpm.d/www.conf

HEALTHCHECK --interval=10s --timeout=3s --retries=3 \
  CMD pidof php-fpm || exit 1

USER appuser
WORKDIR /var/www/html

RUN git config --global --add safe.directory /var/www/html

CMD ["php-fpm"]
