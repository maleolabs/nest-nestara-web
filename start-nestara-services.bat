@echo off
setlocal
cd /d "%~dp0"

start "Nestara ngrok" cmd /k "ngrok http --host-header=rewrite nestara.test:443 --url=https://easily-shining-spaniel.ngrok-free.app"
start "Nestara scheduler" cmd /k "php artisan schedule:work"
start "Nestara queue" cmd /k "php artisan queue:work"

echo Ngrok, scheduler, queue started.
echo Close their command windows to stop services.
endlocal
