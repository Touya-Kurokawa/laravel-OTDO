FROM php:8.3-apache

# 必要なパッケージ
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /var/www/html

# .envを先にコピー
COPY .env.production .env

# アプリケーション全体をコピー
COPY . .

# Composer install（エラー出るなら --no-scripts で検証してもOK）
RUN composer install --no-dev --optimize-autoloader

# Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# パーミッション
RUN chown -R www-data:www-data storage bootstrap/cache
