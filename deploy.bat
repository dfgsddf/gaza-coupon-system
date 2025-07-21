@echo off
echo 🚀 Starting Gaza Coupon System Deployment Preparation...

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] This script must be run from the Laravel project root directory
    pause
    exit /b 1
)

echo [INFO] Preparing Gaza Coupon System for production deployment...

REM Step 1: Install production dependencies
echo [INFO] Installing production dependencies...
composer install --optimize-autoloader --no-dev --no-interaction

if %errorlevel% neq 0 (
    echo [ERROR] Failed to install dependencies
    pause
    exit /b 1
)

REM Step 2: Clear all caches
echo [INFO] Clearing application caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

REM Step 3: Optimize for production
echo [INFO] Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

REM Step 4: Create storage link
echo [INFO] Creating storage link...
php artisan storage:link

REM Step 5: Remove development files
echo [INFO] Removing development files...
if exist "node_modules" (
    rmdir /s /q node_modules
)

if exist "package-lock.json" (
    del package-lock.json
)

REM Step 6: Create deployment package
echo [INFO] Creating deployment package...
set DEPLOYMENT_NAME=gaza-coupon-system-%date:~-4,4%%date:~-10,2%%date:~-7,2%-%time:~0,2%%time:~3,2%%time:~6,2%.zip
set DEPLOYMENT_NAME=%DEPLOYMENT_NAME: =0%

REM Create zip file (requires 7-Zip or similar)
echo [INFO] Creating deployment package: %DEPLOYMENT_NAME%
echo [INFO] Please manually create a zip file with the following contents:
echo.
echo Files to include:
echo - All project files EXCEPT:
echo   - .git folder
echo   - node_modules folder
echo   - .env.example
echo   - README.md
echo   - DEPLOYMENT_GUIDE.md
echo   - deploy.bat
echo   - deploy.sh
echo   - storage/logs/*
echo   - storage/framework/cache/*
echo   - storage/framework/sessions/*
echo   - storage/framework/views/*
echo   - tests folder
echo   - phpunit.xml
echo   - .gitignore
echo   - .editorconfig
echo   - vite.config.js
echo   - package.json
echo.

REM Step 7: Create production environment template
echo [INFO] Creating production environment template...
(
echo APP_NAME="Gaza Coupon System"
echo APP_ENV=production
echo APP_KEY=base64:YOUR_APP_KEY_HERE
echo APP_DEBUG=false
echo APP_URL=https://your-domain.aqeethub.com
echo.
echo LOG_CHANNEL=stack
echo LOG_DEPRECATIONS_CHANNEL=null
echo LOG_LEVEL=error
echo.
echo DB_CONNECTION=mysql
echo DB_HOST=localhost
echo DB_PORT=3306
echo DB_DATABASE=your_database_name
echo DB_USERNAME=your_database_username
echo DB_PASSWORD=your_database_password
echo.
echo BROADCAST_DRIVER=log
echo CACHE_DRIVER=file
echo FILESYSTEM_DISK=local
echo QUEUE_CONNECTION=sync
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=120
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=your-smtp-host
echo MAIL_PORT=587
echo MAIL_USERNAME=your-email@domain.com
echo MAIL_PASSWORD=your-email-password
echo MAIL_ENCRYPTION=tls
echo MAIL_FROM_ADDRESS=noreply@yourdomain.com
echo MAIL_FROM_NAME="${APP_NAME}"
) > .env.production.template

echo [INFO] Production environment template created: .env.production.template

REM Step 8: Create deployment instructions
echo [INFO] Creating deployment instructions...
(
echo 🚀 Gaza Coupon System - Aqeet Hub Deployment Instructions
echo.
echo 📋 PRE-DEPLOYMENT CHECKLIST:
echo 1. Ensure you have an Aqeet Hub account with PHP 8.3+ and MySQL 8.0+
echo 2. Have your domain ready and SSL certificate configured
echo 3. Prepare your database credentials
echo.
echo 📤 UPLOAD STEPS:
echo 1. Login to your Aqeet Hub control panel
echo 2. Navigate to File Manager
echo 3. Upload the deployment package to your domain directory
echo 4. Extract the files if uploaded as zip
echo.
echo 🗄️ DATABASE SETUP:
echo 1. Create a MySQL database in Aqeet Hub
echo 2. Note down database name, username, and password
echo 3. Import the database structure using phpMyAdmin or SSH
echo.
echo ⚙️ CONFIGURATION:
echo 1. Rename .env.production.template to .env
echo 2. Update the following in .env:
echo    - APP_URL=https://your-domain.aqeethub.com
echo    - DB_DATABASE=your_database_name
echo    - DB_USERNAME=your_database_username
echo    - DB_PASSWORD=your_database_password
echo    - MAIL settings for your email provider
echo.
echo 🔑 APPLICATION KEY:
echo 1. Generate a new application key:
echo    php artisan key:generate
echo.
echo 🗃️ DATABASE MIGRATION:
echo 1. Run database migrations:
echo    php artisan migrate --force
echo 2. Seed the database with sample data:
echo    php artisan db:seed --force
echo.
echo 🔒 FINAL OPTIMIZATION:
echo 1. Set proper file permissions:
echo    chmod -R 755 storage/
echo    chmod -R 755 bootstrap/cache/
echo    chmod -R 755 public/
echo 2. Create storage link:
echo    php artisan storage:link
echo 3. Clear and cache configurations:
echo    php artisan config:cache
echo    php artisan route:cache
echo    php artisan view:cache
echo.
echo ✅ VERIFICATION:
echo 1. Test all user roles (Admin, Charity, Store, Beneficiary)
echo 2. Verify all core features are working
echo 3. Check email functionality
echo 4. Test file uploads if applicable
echo.
echo 📞 SUPPORT:
echo - Aqeet Hub Support: Your hosting provider
echo - Project Documentation: PROJECT_DOCUMENTATION.md
echo - Deployment Guide: DEPLOYMENT_GUIDE.md
echo.
echo 🎯 Your Gaza Coupon System should now be live at: https://your-domain.aqeethub.com
) > DEPLOYMENT_INSTRUCTIONS.txt

echo [INFO] Deployment instructions created: DEPLOYMENT_INSTRUCTIONS.txt

REM Step 9: Final status
echo.
echo [INFO] 🎉 Deployment preparation completed successfully!
echo.
echo [INFO] 📦 Files created:
echo    - .env.production.template (environment template)
echo    - DEPLOYMENT_INSTRUCTIONS.txt (step-by-step guide)
echo.
echo [INFO] 📋 Next steps:
echo    1. Create a zip file of your project (excluding development files)
echo    2. Upload the zip file to Aqeet Hub
echo    3. Follow DEPLOYMENT_INSTRUCTIONS.txt
echo    4. Configure your domain and database
echo    5. Test all features after deployment
echo.
echo [WARNING] ⚠️  Remember to:
echo    - Update .env with your actual database credentials
echo    - Configure your email settings
echo    - Set up SSL certificate
echo    - Test all user roles after deployment
echo.
echo [INFO] 🚀 Your Gaza Coupon System is ready for Aqeet Hub deployment!
echo.
pause 