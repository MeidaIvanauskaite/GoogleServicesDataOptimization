#!/bin/bash

echo "⏳ Waiting for MySQL to be ready..."

until mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" "$DB_DATABASE" > /dev/null 2>&1; do
  echo -n "."
  sleep 1
done

echo " ✅ MySQL is ready."
