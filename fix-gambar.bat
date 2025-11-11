@echo off
echo ========================================
echo FIX GAMBAR TIDAK MUNCUL
echo ========================================
echo.

echo [1/6] Stopping any running server...
taskkill /F /IM php.exe 2>nul
timeout /t 2 >nul

echo.
echo [2/6] Removing old storage link...
if exist "public\storage" (
    rmdir "public\storage"
    echo Old link removed
) else (
    echo No old link found
)

echo.
echo [3/6] Creating new storage link...
php artisan storage:link

echo.
echo [4/6] Clearing all cache...
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo.
echo [5/6] Verifying files...
echo Checking storage folder:
dir "storage\app\public\gallery" | find ".jpg"
echo.
echo Checking public symlink:
dir "public\storage" | find "gallery"

echo.
echo [6/6] Starting Laravel server...
echo.
echo ========================================
echo SERVER WILL START NOW
echo ========================================
echo.
echo Open browser and go to:
echo http://127.0.0.1:8000/galeri
echo.
echo Then press: Ctrl + Shift + R (Hard Refresh)
echo.
echo Press Ctrl+C to stop server
echo ========================================
echo.

php artisan serve
