#!/bin/bash

echo "🧱 Starting Docker containers..."
docker-compose up --build -d

echo "📦 Installing Composer dependencies..."
docker exec -it php_app composer install
docker exec -it php_app chmod -R 775 storage bootstrap/cache
docker exec -it php_app chown -R www-data:www-data storage bootstrap/cache

echo "⚙️ Running migrations..."
docker exec -it php_app php artisan migrate --force
docker exec -it php_app php artisan db:seed --class=UserSeeder

echo "⚡ Installing Node/Vite dependencies..."
npm install

echo "🧪 Starting Vite..."
npm run dev
