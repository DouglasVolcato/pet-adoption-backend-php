FROM php:7.4-apache

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y libpq-dev libzip-dev \
    && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./app /var/www/html/
COPY ./app/my-site.conf /etc/apache2/sites-available/

ENV MYSQL_HOST=${MYSQL_HOST}
ENV MYSQL_DATABASE=${MYSQL_DATABASE}
ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}
ENV PORT=${PORT}
ENV SECRET=${SECRET}
ENV CATS_API_TOKEN=${CATS_API_TOKEN}
ENV DOGS_API_TOKEN=${DOGS_API_TOKEN}

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    a2enmod headers && \
    a2dissite 000-default && \
    a2ensite my-site && \
    service apache2 restart

RUN composer global require phpunit/phpunit
RUN composer global require ramsey/uuid
RUN composer global require egulias/email-validator
RUN composer global require guzzlehttp/guzzle

ENV PATH="${PATH}:/root/.composer/vendor/bin"
EXPOSE ${PORT}
