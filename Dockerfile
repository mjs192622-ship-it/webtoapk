FROM php:8.2-apache

# Install required PHP extensions and system packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        zip \
        gd \
        xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers expires deflate

# Set working directory
WORKDIR /var/www/html

# Copy application files (excluding local SQLite WAL/SHM files)
COPY . /var/www/html/

# Remove Windows SQLite WAL/SHM files that can corrupt the DB on Linux
RUN rm -f /var/www/html/database.sqlite-shm /var/www/html/database.sqlite-wal

# Create required writable directories with proper permissions
RUN mkdir -p /var/www/html/uploads \
             /var/www/html/output \
             /var/www/html/builds \
             /var/www/html/template \
    && touch /var/www/html/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/uploads \
    && chmod -R 777 /var/www/html/output \
    && chmod -R 777 /var/www/html/builds \
    && chmod 666 /var/www/html/database.sqlite

# Configure Apache virtual host
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options -Indexes +FollowSymLinks\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# PHP configuration for uploads and execution time
RUN echo "upload_max_filesize = 10M\n\
post_max_size = 12M\n\
max_execution_time = 120\n\
memory_limit = 256M\n\
display_errors = Off\n\
log_errors = On\n\
error_log = /var/www/html/error.log" > /usr/local/etc/php/conf.d/custom.ini

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
