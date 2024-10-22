# Dockerfile
FROM php:8.3-fpm

# Instalar las dependencias necesarias y la extensión SSH2
RUN apt-get update && apt-get install -y \
    libssh2-1-dev \
    libzip-dev \
    && pecl install ssh2 \
    && docker-php-ext-enable ssh2 \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Crear el usuario 'sail'
RUN useradd -m sail

# Cambiar a usuario 'sail'
USER sail
