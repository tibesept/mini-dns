FROM php:8.2-apache

# Включаем модуль mod_rewrite для Apache (полезно для роутинга, если понадобится)
RUN a2enmod rewrite

# Устанавливаем и включаем расширение pdo_mysql для работы с БД
RUN docker-php-ext-install pdo pdo_mysql

# Копируем исходный код в папку сервера
COPY . /var/www/html/

# Назначаем права для Apache
RUN chown -R www-data:www-data /var/www/html
