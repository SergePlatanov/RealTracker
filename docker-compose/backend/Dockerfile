FROM php:8.4-fpm

# UID/GID пользователя на хосте (владельца bind-маунта), чтобы php-fpm мог писать в storage
ARG HOST_UID=1000
ARG HOST_GID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    mariadb-client \
    vim \
    unzip \
    git \
    curl && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo_mysql pdo mbstring zip exif pcntl intl bcmath gd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    # Clear cache
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application (uid/gid совпадают с хостовым пользователем bind-маунта)
RUN groupadd -g ${HOST_GID} www && useradd -u ${HOST_UID} -ms /bin/bash -g www www && \
    printf "[www]\nuser = www\ngroup = www\n" > /usr/local/etc/php-fpm.d/zz-realtracker.conf

WORKDIR /var/www
EXPOSE 9000
CMD ["php-fpm"]
