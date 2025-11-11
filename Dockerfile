FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Copy all template files to the web root
COPY templates/ /var/www/html/

# Enable Apache mod_rewrite if needed (not required for this demo, but good practice)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Apache will start automatically with the base image's default CMD

