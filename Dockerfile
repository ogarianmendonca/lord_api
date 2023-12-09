FROM php:7.4-fpm-alpine

RUN echo "Instalação de pacotes adicionais"
RUN apk --no-cache add \
    postgresql-dev \
    zlib-dev \
    libzip-dev

RUN echo "Instalação de extensões PHP"
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip

RUN echo "Instalação do Composer"
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN echo "Cópia de arquivos do aplicativo"
COPY . /src
WORKDIR /src

RUN echo "Executa o Composer Install"
RUN composer install --no-dev --optimize-autoloader

# O comando CMD foi movido para docker-compose.yml
# CMD php -S 0.0.0.0:8080 public/index.php
