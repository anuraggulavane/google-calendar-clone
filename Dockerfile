FROM php:8.2-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy project files
COPY . /app
WORKDIR /app

# Use PHP's built-in server — Railway injects PORT
CMD php -S 0.0.0.0:${PORT:-8080} -t /app
