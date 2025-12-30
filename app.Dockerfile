FROM php:8.5-fpm
RUN apt-get update && apt-get install -y \
    curl zip unzip git \
    libzip-dev zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev \
    mariadb-client \
    libmagickwand-dev libmagickcore-dev \
    openssh-client \
    --no-install-recommends

RUN docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    pcntl \
    ftp \
    opcache

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd

RUN pecl install imagick redis xdebug \
 && docker-php-ext-enable imagick redis xdebug

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y nodejs

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
