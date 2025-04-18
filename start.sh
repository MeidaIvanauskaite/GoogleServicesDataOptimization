#!/bin/bash

echo "📦 Installing Node & Composer dependencies..."
npm install
docker exec -it php-app composer install

echo "🧱 Starting Docker containers..."
docker-compose up --build -d

echo "⚙️ Starting Vite..."
npm run dev

#! ./start.sh
#! http://localhost/
