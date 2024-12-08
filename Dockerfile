# ベースイメージとしてPHPの公式イメージを使用
FROM php:8.1-fpm

# 作業ディレクトリを設定
WORKDIR /var/www/html

# 必要なシステム依存関係をインストール
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev 

# PHP拡張をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションのコピー
COPY . .

# Composerで依存関係をインストール
RUN composer install --optimize-autoloader --no-dev

# パーミッションの設定
RUN chown -R www-data:www-data /var/www/html/storage

# ポートを公開
EXPOSE 8000

# 起動コマンド
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
