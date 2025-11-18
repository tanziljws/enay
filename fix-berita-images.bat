@echo off
echo =========================================
echo FIX GAMBAR BERITA TIDAK MUNCUL
echo =========================================
echo.
echo [1/7] Stopping any running server...
taskkill /F /IM php.exe 2>nul
timeout /t 2 >nul
echo.
echo [2/7] Removing old storage link...
if exist "public\storage" (
    rmdir "public\storage"
    echo Old link removed
) else (
    echo No old link found
)
echo.
echo [3/7] Creating new storage link...
php artisan storage:link
echo.
echo [4/7] Clearing all cache...
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
echo.
echo [5/7] Checking news images in storage...
echo.
echo Checking storage/app/public/news:
if exist "storage\app\public\news" (
    dir "storage\app\public\news" /B
) else (
    echo Folder news tidak ada, membuat folder...
    mkdir "storage\app\public\news"
    echo Folder news berhasil dibuat
)
echo.
echo [6/7] Syncing storage to public...
xcopy "storage\app\public\*" "public\storage\" /E /I /Y /Q
echo.
echo [7/7] Starting Laravel server...
echo.
echo =========================================
echo SERVER WILL START NOW
echo =========================================
echo.
echo Open browser and go to:
echo http://127.0.0.1:8000/news
echo.
echo Then press: Ctrl + Shift + R (Hard Refresh)
echo.
echo Press Ctrl+C to stop server
echo =========================================
echo.
php artisan serve
