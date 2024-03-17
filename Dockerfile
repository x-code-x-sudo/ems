FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        supervisor \
        zlib1g-dev \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-install zip


RUN apt-get install -y nodejs npm

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath gd

RUN apt-get update && apt-get install --no-cache supervisor

RUN mkdir -p "/etc/supervisor/logs"


COPY /supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www


#COPY composer.json composer.lock ./
#RUN composer install --no-dev --optimize-autoloader

# Run composer install
RUN composer install
# RUN composer dump-autoload

# RUN npm install
# RUN npm install -D @tailwindcss/typography
# RUN npm run build

#RUN composer install --no-autoloader
#RUN php artisan storage:link

# RUN mkdir /var/www/resources/build

# RUN cp -R /var/www/public/build/* /var/www/resources/build

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

#RUN chmod -R 755 /var/www/public
#RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]

