#!/bin/bash

echo "🧱 Starting Docker containers..."
docker-compose up --build -d

echo "📦 Installing Composer dependencies..."
docker exec -it php_app composer install
docker exec -it php_app chmod -R 775 storage bootstrap/cache
docker exec -it php_app chown -R www-data:www-data storage bootstrap/cache
docker exec -it php_app chmod +x wait-for-mysql.sh

echo "⏳ Waiting for MySQL to be ready..."
docker exec -e DB_HOST=db -e DB_DATABASE=laravel -e DB_USERNAME=laravel_user -e DB_PASSWORD=laravel_password php_app wait-for-mysql.sh

echo "⚙️ Running migrations..."
docker exec -it php_app php artisan migrate --force
docker exec -it php_app php artisan db:seed --class=UserSeeder

echo "⚡ Installing Node/Vite dependencies..."
npm install

echo "🧪 Starting Vite..."
npm run dev
