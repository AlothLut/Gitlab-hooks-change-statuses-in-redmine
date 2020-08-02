FROM php:7.2-fpm

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    msmtp \
    curl \
    && pecl install \
        xdebug \
    && docker-php-ext-enable \
        xdebug

RUN apt-get install -y cron && \
    apt-get install -y supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose port 9000 and start php-fpm server
EXPOSE 9000
#CMD ["php-fpm"]
CMD  service supervisor start; php-fpm
