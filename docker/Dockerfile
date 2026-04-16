FROM debian:bookworm-slim

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    APP_ENV=production \
    MYSQL_DATADIR=/var/lib/mysql \
    MYSQL_DATABASE=laravel \
    MYSQL_USER=laravel \
    MYSQL_PASSWORD=secret

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    git \
    gnupg \
    lsb-release \
    unzip \
    && curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    mariadb-server \
    php8.4-cli \
    php8.4-curl \
    php8.4-fpm \
    php8.4-gd \
    php8.4-intl \
    php8.4-mbstring \
    php8.4-mysql \
    php8.4-opcache \
    php8.4-readline \
    php8.4-xml \
    php8.4-zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate --force \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/laravel-bootstrap.sh /usr/local/bin/laravel-bootstrap.sh
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/laravel-bootstrap.sh

EXPOSE 80 3306

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
