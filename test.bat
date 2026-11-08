@echo off
if exist "C:\laragon\bin\php\php-8.5.5-Win32-vs17-x64\php.exe" (
    set "PATH=C:\laragon\bin\php\php-8.5.5-Win32-vs17-x64;%PATH%"
)
echo Menjalankan Test Suite...
php artisan test
