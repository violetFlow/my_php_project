# ベースイメージとして PHP 8.2 FPM を使用
FROM php:8.2-fpm

# 必要なPHP拡張をインストール
RUN docker-php-ext-install pdo pdo_mysql

# 作業ディレクトリを設定
WORKDIR /var/www/html

# Composerをインストール（最新バージョンから直接コピー）
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションファイルをコンテナにコピー
COPY . .

# Composerの依存関係をインストール
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

# コンテナが公開するポートを指定
EXPOSE 9000