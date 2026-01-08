FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

COPY docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG UID
ARG GID

RUN if ! getent group ${GID} >/dev/null; then \
    groupadd -g ${GID} appgroup; \
    fi \
    && if ! id -u ${UID} >/dev/null 2>&1; then \
    useradd -u ${UID} -g ${GID} -m appuser; \
    fi

USER appuser
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN composer run-script post-install-cmd || true

EXPOSE 9000

COPY docker/entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]
