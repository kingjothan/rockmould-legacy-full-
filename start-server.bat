@echo off
title Rockmould Server
cd /d C:\xampp\htdocs\rockmould-legacy-main
echo Starting Rockmould server...
echo Open your browser at: http://127.0.0.1:8000
echo.
echo DO NOT close this window - closing it stops the server.
echo.
php artisan serve
pause
