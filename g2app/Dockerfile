# Base image amb PHP + extensions comunes per Laravel
FROM php:8.2-cli

# Instal·lem dependències i utilitats
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    zip \
    npm \
    && docker-php-ext-install zip pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instal·lem Composer globalment
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definim el directori de treball
WORKDIR /app

# Copiem tot el codi a /app
COPY . .

# Instal·lem les dependències PHP i Node i compilem assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Fem la migració de base de dades aquí, només si tens base de dades disponible en build (normalment no)
# Si no, fes-la a runtime en start (recomanat no fer migracions a build)

# Exposem port que Render pot usar. Render assigna el port via variable d'entorn $PORT,
# però per exposar el port podem posar un genèric, per exemple 8000
EXPOSE 8000

# Start command que agafi el port de Render (a runtime)
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
