FROM php:7.1-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project into Apache web root
COPY . /var/www/html/

# Optional: set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Set the working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80
