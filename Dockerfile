FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \    
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
# NOTE: Added pdo_pgsql here
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
# Using --no-scripts to prevent errors during build phase
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN npm install && npm run build

# Set permissions for Laravel
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data /var/www

# Expose port
EXPOSE 80

# --- START COMMAND ---
# 1. Clear old config (very important so it sees Neon)
# 2. Run migrations
# 3. Start the server
CMD php artisan config:clear && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=80