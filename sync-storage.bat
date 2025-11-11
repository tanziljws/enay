@echo off
echo ========================================
echo SYNC STORAGE FILES
echo ========================================
echo.
echo Copying files from storage/app/public to public/storage...
echo.

xcopy "storage\app\public\*" "public\storage\" /E /I /Y /Q

echo.
echo ========================================
echo DONE! Files synced successfully.
echo ========================================
echo.
echo Now refresh your browser: Ctrl + Shift + R
echo.
pause
