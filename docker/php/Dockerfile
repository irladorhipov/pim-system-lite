FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    wget \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs

RUN pecl install redis && docker-php-ext-enable redis

RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log
RUN chown -R www-data:www-data /var/www/html/var/cache /var/www/html/var/log
RUN chmod -R 775 /var/www/html/var/cache /var/www/html/var/log

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public
RUN chmod -R 775 /var/www/html/var /var/www/html/public

EXPOSE 9000

CMD ["php-fpm"]