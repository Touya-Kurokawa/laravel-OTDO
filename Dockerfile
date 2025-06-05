FROM php:8.3-apache

# 必要なパッケージ
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www/html

# .env を先に配置（Render の .env.production を .env にする）
COPY .env.production .env

# アプリケーション全体をコピー
COPY . .

# Composer install （--no-scripts で artisan 関連の実行回避）
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Laravelのstorageやbootstrap/cacheの事前作成
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# セッションマイグレーション実行
RUN php artisan migrate --force

# Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite
