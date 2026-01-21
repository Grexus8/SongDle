FROM php:8.4-fpm

# 1. Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    libssl-dev \
    libjemalloc2 \
    $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

# 2. Extensiones básicas de PHP
RUN docker-php-ext-install pdo pdo_mysql xml ctype zip

# 3. Instalar extensión de MongoDB (Compilación Manual para evitar errores)
RUN pecl bundle mongodb \
    && cd mongodb \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable mongodb \
    && cd .. && rm -rf mongodb

# 4. Iniciar Xdebug (Opcional: descomenta si lo necesitas y añádelo con pecl install xdebug antes)
# RUN pecl install xdebug && docker-php-ext-enable xdebug

# 5. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# 6. Configuración PHP
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# 7. Crear usuario y grupo (CORREGIDO)
# Usamos -f para forzar éxito si el grupo existe
# Usamos '|| true' en useradd para ignorar error si el usuario ya existe
RUN groupadd -f -g 1000 app
RUN useradd -u 1000 -g app -m -s /bin/bash app || true
RUN chown -R app:app /var/www/html

# 8. Directorio de trabajo
WORKDIR /var/www/html