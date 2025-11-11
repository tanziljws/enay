@echo off
echo ========================================
echo RESTART SERVER & FIX ERROR
echo ========================================
echo.

echo [1/5] Stopping all PHP processes...
taskkill /F /IM php.exe 2>nul
timeout /t 2 >nul

echo.
echo [2/5] Clearing all cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [3/5] Syncing storage files...
xcopy "storage\app\public\*" "public\storage\" /E /I /Y /Q

echo.
echo [4/5] Verifying controller...
php -l app\Http\Controllers\UserInteractionController.php

echo.
echo [5/5] Starting server...
echo.
echo ========================================
echo SERVER STARTING...
echo ========================================
echo.
echo Open browser: http://127.0.0.1:8000/galeri
echo Then press: Ctrl + Shift + R
echo.
echo Press Ctrl+C to stop server
echo ========================================
echo.

php artisan serve
