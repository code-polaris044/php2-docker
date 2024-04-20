# インストールするイメージ
FROM php:apache

# 拡張モジュールをインストール
RUN apt-get update && apt-get install -y \
  && docker-php-ext-install pdo pdo_mysql mysqli
