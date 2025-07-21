#!/bin/bash

# Gaza Coupon System - Deployment Script for Aqeet Hub
# This script prepares your Laravel project for production deployment

echo "🚀 Starting Gaza Coupon System Deployment Preparation..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Preparing Gaza Coupon System for production deployment..."

# Step 1: Install production dependencies
print_status "Installing production dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -ne 0 ]; then
    print_error "Failed to install dependencies"
    exit 1
fi

# Step 2: Clear all caches
print_status "Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Step 3: Optimize for production
print_status "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 4: Set proper permissions
print_status "Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/

# Step 5: Create storage link
print_status "Creating storage link..."
php artisan storage:link

# Step 6: Remove development files
print_status "Removing development files..."
if [ -d "node_modules" ]; then
    rm -rf node_modules/
fi

if [ -f "package-lock.json" ]; then
    rm package-lock.json
fi

# Step 7: Create deployment package
print_status "Creating deployment package..."
DEPLOYMENT_NAME="gaza-coupon-system-$(date +%Y%m%d-%H%M%S).zip"

# Create zip file excluding development files
zip -r "$DEPLOYMENT_NAME" . \
    -x "*.git*" \
    -x "node_modules/*" \
    -x ".env.example" \
    -x "README.md" \
    -x "DEPLOYMENT_GUIDE.md" \
    -x "deploy.sh" \
    -x "storage/logs/*" \
    -x "storage/framework/cache/*" \
    -x "storage/framework/sessions/*" \
    -x "storage/framework/views/*" \
    -x "tests/*" \
    -x "phpunit.xml" \
    -x ".gitignore" \
    -x ".editorconfig" \
    -x "vite.config.js" \
    -x "package.json"

if [ $? -eq 0 ]; then
    print_status "Deployment package created: $DEPLOYMENT_NAME"
else
    print_error "Failed to create deployment package"
    exit 1
fi

# Step 8: Create production environment template
print_status "Creating production environment template..."
cat > .env.production.template << 'EOF'
APP_NAME="Gaza Coupon System"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.aqeethub.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
EOF

print_status "Production environment template created: .env.production.template"

# Step 9: Create deployment instructions
print_status "Creating deployment instructions..."
cat > DEPLOYMENT_INSTRUCTIONS.txt << 'EOF'
🚀 Gaza Coupon System - Aqeet Hub Deployment Instructions

📋 PRE-DEPLOYMENT CHECKLIST:
1. Ensure you have an Aqeet Hub account with PHP 8.3+ and MySQL 8.0+
2. Have your domain ready and SSL certificate configured
3. Prepare your database credentials

📤 UPLOAD STEPS:
1. Login to your Aqeet Hub control panel
2. Navigate to File Manager
3. Upload the deployment package to your domain directory
4. Extract the files if uploaded as zip

🗄️ DATABASE SETUP:
1. Create a MySQL database in Aqeet Hub
2. Note down database name, username, and password
3. Import the database structure using phpMyAdmin or SSH

⚙️ CONFIGURATION:
1. Rename .env.production.template to .env
2. Update the following in .env:
   - APP_URL=https://your-domain.aqeethub.com
   - DB_DATABASE=your_database_name
   - DB_USERNAME=your_database_username
   - DB_PASSWORD=your_database_password
   - MAIL settings for your email provider

🔑 APPLICATION KEY:
1. Generate a new application key:
   php artisan key:generate

🗃️ DATABASE MIGRATION:
1. Run database migrations:
   php artisan migrate --force
2. Seed the database with sample data:
   php artisan db:seed --force

🔒 FINAL OPTIMIZATION:
1. Set proper file permissions:
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   chmod -R 755 public/
2. Create storage link:
   php artisan storage:link
3. Clear and cache configurations:
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

✅ VERIFICATION:
1. Test all user roles (Admin, Charity, Store, Beneficiary)
2. Verify all core features are working
3. Check email functionality
4. Test file uploads if applicable

📞 SUPPORT:
- Aqeet Hub Support: Your hosting provider
- Project Documentation: PROJECT_DOCUMENTATION.md
- Deployment Guide: DEPLOYMENT_GUIDE.md

🎯 Your Gaza Coupon System should now be live at: https://your-domain.aqeethub.com
EOF

print_status "Deployment instructions created: DEPLOYMENT_INSTRUCTIONS.txt"

# Step 10: Final status
echo ""
print_status "🎉 Deployment preparation completed successfully!"
echo ""
print_status "📦 Files created:"
echo "   - $DEPLOYMENT_NAME (deployment package)"
echo "   - .env.production.template (environment template)"
echo "   - DEPLOYMENT_INSTRUCTIONS.txt (step-by-step guide)"
echo ""
print_status "📋 Next steps:"
echo "   1. Upload $DEPLOYMENT_NAME to Aqeet Hub"
echo "   2. Follow DEPLOYMENT_INSTRUCTIONS.txt"
echo "   3. Configure your domain and database"
echo "   4. Test all features after deployment"
echo ""
print_warning "⚠️  Remember to:"
echo "   - Update .env with your actual database credentials"
echo "   - Configure your email settings"
echo "   - Set up SSL certificate"
echo "   - Test all user roles after deployment"
echo ""
print_status "🚀 Your Gaza Coupon System is ready for Aqeet Hub deployment!" 