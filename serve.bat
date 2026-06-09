@echo off
cd /d "%~dp0"
if not exist "api\stripe-config.php" (
  echo Stripe config puudub. Kopeerin naite...
  copy /Y "api\stripe-config.example.php" "api\stripe-config.php" >nul
  echo Taideta api\stripe-config.php Stripe secret votmega.
)
if not exist "api\mail-config.php" (
  copy /Y "api\mail-config.example.php" "api\mail-config.php" >nul
  echo Taideta api\mail-config.php Web3Forms votmega.
)
echo.
echo Tarukoda kohalik server:
echo   http://127.0.0.1:8080/kontakt.html
echo   http://127.0.0.1:8080/tellimus.html
echo.
php -S 127.0.0.1:8080
