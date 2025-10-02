@echo off
echo ========================================
echo Laravel Application Deployment Script
echo ========================================
echo.

REM Change to script directory
cd /d "%~dp0"

echo [1/6] Clearing all caches...
php artisan optimize:clear
if %errorlevel% neq 0 (
    echo ERROR: Failed to clear caches
    pause
    exit /b 1
)
echo Caches cleared successfully!
echo.

echo [2/6] Caching configuration for production...
php artisan config:cache
if %errorlevel% neq 0 (
    echo ERROR: Failed to cache configuration
    pause
    exit /b 1
)
echo Configuration cached successfully!
echo.

echo [3/6] Caching routes for production...
php artisan route:cache
if %errorlevel% neq 0 (
    echo ERROR: Failed to cache routes
    pause
    exit /b 1
)
echo Routes cached successfully!
echo.

echo [4/6] Caching views for production...
php artisan view:cache
if %errorlevel% neq 0 (
    echo ERROR: Failed to cache views
    pause
    exit /b 1
)
echo Views cached successfully!
echo.

echo [5/6] Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERROR: Failed to run migrations
    pause
    exit /b 1
)
echo Migrations completed successfully!
echo.

echo [6/6] Creating storage link...
php artisan storage:link
if %errorlevel% neq 0 (
    echo WARNING: Storage link may already exist or failed to create
)
echo Storage link process completed!
echo.

echo ========================================
echo Deployment completed successfully!
echo ========================================
echo.
echo IMPORTANT REMINDERS:
echo 1. Update your .env file with production settings
echo 2. Set SSLCOMMERZ_SANDBOX=false for live payments
echo 3. Update SSL Commerz credentials (STORE_ID and STORE_PASSWORD)
echo 4. Ensure proper file permissions are set
echo 5. Test the application thoroughly
echo.
pause