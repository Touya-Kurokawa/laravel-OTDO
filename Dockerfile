FROM php:8.3-apache

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer インストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravelのプロジェクトを配置
COPY . /var/www/html

WORKDIR /var/www/html
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader

# ApacheのドキュメントルートをLaravelのpublicに設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Laravelのパーミッション設定（必要であれば）
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
