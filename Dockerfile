FROM php:8.3-apache

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの指定
WORKDIR /var/www/html

# ✅ .env.production を .env にコピー（この位置）
COPY .env.production .env

# プロジェクトファイルをコピー
COPY . .

# vendorディレクトリ生成
RUN composer install --no-dev --optimize-autoloader

# パーミッション設定
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite
