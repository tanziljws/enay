@echo off
echo =========================================
echo SYNC FOTO BARU KE PUBLIC
echo =========================================
echo.
echo Menyinkronkan foto dari storage ke public...
echo.
xcopy storage\app\public\gallery\* public\storage\gallery\ /E /I /Y /Q
xcopy storage\app\public\news\* public\storage\news\ /E /I /Y /Q
xcopy storage\app\public\teachers\* public\storage\teachers\ /E /I /Y /Q
echo.
echo =========================================
echo SELESAI!
echo =========================================
echo.
echo Foto-foto baru sudah tersinkronisasi.
echo Silakan refresh browser: Ctrl + Shift + R
echo.
pause
