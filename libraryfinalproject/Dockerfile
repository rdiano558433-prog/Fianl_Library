FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libzip-dev libonig-dev libxml2-dev libpng-dev \
    && docker-php-ext-install \
        pdo pdo_mysql zip mbstring xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set Laravel public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess
RUN printf "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project
COPY . .

# Env setup
RUN cp .env.example .env || true

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend
RUN npm install --legacy-peer-deps
RUN npm run build || true

# Fix permissions
RUN mkdir -p database storage bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache

# Laravel setup
RUN php artisan key:generate --no-interaction || true \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

RUN php artisan storage:link || true


EXPOSE 80

CMD ["apache2-foreground"]