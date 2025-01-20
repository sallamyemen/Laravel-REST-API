# Используем официальный образ PHP с FPM
FROM php:8.1-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    zip \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

# Копируем все файлы проекта в контейнер
COPY . .

# Устанавливаем зависимости через Composer
RUN composer install

# Устанавливаем права на запуск
RUN chmod +x docker-entrypoint.sh

# Настроим точку входа
ENTRYPOINT ["./docker-entrypoint.sh"]

# Стартуем PHP FPM
CMD ["php-fpm"]
