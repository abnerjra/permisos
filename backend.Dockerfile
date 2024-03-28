FROM php:8.2-fpm

# Crear un usuario y grupo personalizado
ARG USER_ID
ARG GROUP_ID
ARG USER_NAME
ARG GROUP_NAME

# Permite la instalación de composer sin ser root
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV TZ=America/Mexico_City

# Nos movemos a /var/www/
WORKDIR /var/www/html

# Crea grupo y usuario
RUN groupadd -g $GROUP_ID $GROUP_NAME && \
    useradd -u $USER_ID -g $GROUP_ID -m $USER_NAME

# Copiamos los archivos composer.json a /var/www/html
COPY ./backend/composer*.json .

# Instala dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    unzip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    curl \
    libpq-dev \
    cron \
    tzdata

# Instala Extensiones
RUN docker-php-ext-install zip pgsql pdo pdo_pgsql gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg 

RUN chown -R $USER_NAME:$GROUP_NAME /var/www/html/

# Asigna usuario 
USER $USER_NAME

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalamos dependendencias de composer
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copiamos todos los archivos de la carpeta actual de nuestra
COPY ./backend .

# Exponemos el puerto 9000 a la network
EXPOSE 9000