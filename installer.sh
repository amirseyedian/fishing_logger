#!/bin/bash

APP_DIR="/var/www/fishing_logger"
WEB_USER="www-data"

cd "$APP_DIR" || { echo "App directory not found!"; exit 1; }


php artisan migrate:fresh --force
php artisan db:seed --force
php artisan key:generate
php artisan storage:link


mkdir -p public/storage/temp_trip_images
mkdir -p public/storage/trip_images
mkdir -p storage/app/private/import/files


cp "$APP_DIR"/catches.json "$APP_DIR"/images.json "$APP_DIR"/trips.json storage/app/private/import/files/ 2>/dev/null


chown -R $WEB_USER:$WEB_USER "$APP_DIR"


chown -R $WEB_USER:$WEB_USER "$APP_DIR/public"


chmod 755 /var/www
chmod -R 755 "$APP_DIR/bootstrap/cache"
chmod -R 755 "$APP_DIR/storage"


find "$APP_DIR" -type d -exec chmod 755 {} \;
find "$APP_DIR" -type f -exec chmod 644 {} \;

echo "Laravel app setup complete. Permissions set, storage linked, and import files copied."