FROM php:8.2-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app
COPY . /app

# Crée un .env vide si absent (évite le crash de phpdotenv sur contact.php)
RUN test -f .env || touch .env

CMD ["php", "-S", "0.0.0.0:8001"]
