@echo off
cd /d "%~dp0"

set "PHP_EXE="

for %%P in (
  "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe"
  "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
  "D:\Apps\Server\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe"
  "D:\Apps\Server\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
) do (
  if exist %%~P (
    set "PHP_EXE=%%~P"
    goto :run
  )
)

echo Could not find PHP. Install Laragon or update migrate.bat with your php.exe path.
pause
exit /b 1

:run
echo Running migrations...
"%PHP_EXE%" artisan migrate --force
echo.
pause
