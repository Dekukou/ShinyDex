FROM php:8.3-fpm

# --- Dépendances système ---
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG UID
ARG GID

RUN groupadd -g ${GID} appuser \
 && useradd -u ${UID} -g appuser -m appuser

USER appuser
WORKDIR /var/www/html

# --- Copier les fichiers composer ---
COPY composer.json composer.lock ./

# --- Installer les dépendances PHP ---
RUN composer install --no-scripts --no-interaction --prefer-dist

# --- Copier le reste du projet ---
COPY . .

# --- Créer .env automatiquement si absent ---
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# --- Droits Symfony ---
RUN chown -R www-data:www-data var \
    && chmod -R 775 var

# --- Lancer les scripts Symfony ---
RUN composer run-script post-install-cmd || true

EXPOSE 9000

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]
