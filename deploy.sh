#!/bin/bash

echo "========================================"
echo "Laravel Application Deployment Script"
echo "========================================"
echo

echo "[1/6] Clearing all caches..."
php artisan optimize:clear
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to clear caches"
    exit 1
fi
echo "Caches cleared successfully!"
echo

echo "[2/6] Caching configuration for production..."
php artisan config:cache
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to cache configuration"
    exit 1
fi
echo "Configuration cached successfully!"
echo

echo "[3/6] Caching routes for production..."
php artisan route:cache
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to cache routes"
    exit 1
fi
echo "Routes cached successfully!"
echo

echo "[4/6] Caching views for production..."
php artisan view:cache
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to cache views"
    exit 1
fi
echo "Views cached successfully!"
echo

echo "[5/6] Running database migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to run migrations"
    exit 1
fi
echo "Migrations completed successfully!"
echo

echo "[6/6] Creating storage link..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo "WARNING: Storage link may already exist or failed to create"
fi
echo "Storage link process completed!"
echo

echo "========================================"
echo "Deployment completed successfully!"
echo "========================================"
echo
echo "IMPORTANT REMINDERS:"
echo "1. Update your .env file with production settings"
echo "2. Set SSLCOMMERZ_SANDBOX=false for live payments"
echo "3. Update SSL Commerz credentials (STORE_ID and STORE_PASSWORD)"
echo "4. Ensure proper file permissions are set (755 for directories, 644 for files)"
echo "5. Test the application thoroughly"
echo

# Make sure the script has proper permissions
chmod +x deploy.sh