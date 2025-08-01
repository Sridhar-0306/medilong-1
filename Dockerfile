FROM php:8.1-apache

# Copy the hospital booking files to the web directory
COPY . /var/www/html/

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Install PDO MySQL extension for database connection
RUN docker-php-ext-install pdo pdo_mysql

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
