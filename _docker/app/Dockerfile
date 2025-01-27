# Используем официальный PHP-образ
FROM php:8.2-fpm

# Установим зависимости системы
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Node.js (если используется для фронтенда)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Копирование приложения в контейнер
WORKDIR /var/www/html
COPY . .

# Установим зависимости Laravel
RUN composer install --optimize-autoloader --no-dev

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Права для хранения кэшей и логов
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Установка начальных параметров
RUN php artisan config:clear \
    && php artisan cache:clear

# Открытие порта
EXPOSE 8000

# Запуск приложения
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
