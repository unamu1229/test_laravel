# ベースイメージとしてPHP-FPMを使用
FROM php:8.2-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Composerをインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /var/www/laravel

# 権限の設定
RUN chown -R www-data:www-data /var/www

# Laravelの依存関係をインストールするためのコマンドを実行する準備
COPY . /var/www