FROM php:8.2-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app
COPY . /app

# Crée un .env vide si absent au démarrage (évite le crash de phpdotenv sur contact.php)
# Le RUN ne suffit pas car le volume monte après le build
CMD ["sh", "-c", "test -f .env || touch .env && php -S 0.0.0.0:8001"]
