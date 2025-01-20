#!/bin/bash

# Ждем, пока MySQL станет доступен
until mysql -h mysql -u root -prootpassword -e "SELECT 1"; do
  echo "Waiting for MySQL to be available..."
  sleep 2
done

# Выполняем миграции Laravel
php artisan migrate

# Запускаем приложение
php artisan serve --host=0.0.0.0
