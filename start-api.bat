@echo off
cd /d "%~dp0"
echo Starting Ayoub Jomla API on http://127.0.0.1:8000
echo Press Ctrl+C to stop.
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan serve --host=127.0.0.1 --port=8000
