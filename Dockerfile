FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# --- DATABASE AUTOMATION STEPS ---
# 1. Ensure the database directory exists
# 2. Create an empty SQLite file so Laravel doesn't crash
# 3. Give full permissions so the web server can write to the database
RUN mkdir -p database && \
    touch database/database.sqlite && \
    chmod -R 777 database storage

# Expose port
EXPOSE 80

# The CMD now runs migrations and seeds before starting the server
# --force is required to run migrations in production mode
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=80