# Gaza Coupon System - Deployment Guide

## 🚀 Deployment to Aqeet Hub

### Prerequisites for Aqeet Hub
- **PHP Version:** 8.3 or higher
- **MySQL Version:** 8.0 or higher
- **Web Server:** Apache/Nginx
- **SSL Certificate:** Required for production
- **Domain:** Your Aqeet Hub domain

## 📋 Pre-Deployment Checklist

### 1. **Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set environment to production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.aqeethub.com
```

### 2. **Database Configuration**
```env
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. **File Permissions**
```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
```

### 4. **Optimize for Production**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate optimized autoloader
composer install --optimize-autoloader --no-dev
```

## 🛠️ Aqeet Hub Deployment Steps

### Step 1: **Prepare Your Project**
1. **Create a production branch**
   ```bash
   git checkout -b production
   ```

2. **Remove development files**
   ```bash
   # Remove development dependencies
   composer install --no-dev --optimize-autoloader
   
   # Remove node_modules if not needed in production
   rm -rf node_modules/
   ```

3. **Create deployment package**
   ```bash
   # Create a zip file for upload
   zip -r gaza-coupon-system.zip . -x "*.git*" "node_modules/*" ".env.example" "README.md"
   ```

### Step 2: **Upload to Aqeet Hub**
1. **Access your Aqeet Hub control panel**
2. **Navigate to File Manager**
3. **Upload the project files to your domain directory**
4. **Extract the files if uploaded as zip**

### Step 3: **Database Setup**
1. **Create MySQL database in Aqeet Hub**
2. **Import the database structure:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

### Step 4: **Configuration**
1. **Set up environment variables in Aqeet Hub**
2. **Configure domain and SSL**
3. **Set up email configuration**

### Step 5: **Final Optimization**
```bash
# Run production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

## 🔧 Aqeet Hub Specific Configuration

### **Apache Configuration (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

### **PHP Configuration**
```ini
; Recommended PHP settings for Aqeet Hub
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 64M
post_max_size = 64M
```

## 📊 Post-Deployment Verification

### 1. **Test All User Roles**
- [ ] Admin login and dashboard
- [ ] Charity login and campaigns
- [ ] Store login and transactions
- [ ] Beneficiary login and requests

### 2. **Test Core Features**
- [ ] User registration and authentication
- [ ] Campaign creation and management
- [ ] Request processing workflow
- [ ] Coupon generation and redemption
- [ ] Reporting system
- [ ] Settings management

### 3. **Performance Testing**
- [ ] Page load times
- [ ] Database query performance
- [ ] File upload functionality
- [ ] Email sending capability

### 4. **Security Testing**
- [ ] CSRF protection
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] File upload security

## 🔒 Security Considerations

### **Production Security Checklist**
- [ ] **SSL Certificate** installed and configured
- [ ] **Environment variables** properly set
- [ ] **Debug mode** disabled
- [ ] **Error reporting** disabled
- [ ] **File permissions** properly set
- [ ] **Database credentials** secured
- [ ] **Backup strategy** implemented

### **Recommended Security Headers**
```php
// Add to your middleware or .htaccess
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'"
```

## 📧 Email Configuration

### **SMTP Settings for Aqeet Hub**
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Gaza Coupon System"
```

## 🔄 Backup Strategy

### **Database Backup**
```bash
# Create database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Automated backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p database_name > /backups/backup_$DATE.sql
find /backups -name "*.sql" -mtime +7 -delete
```

### **File Backup**
```bash
# Backup uploaded files
tar -czf files_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public/
```

## 📈 Monitoring and Maintenance

### **Performance Monitoring**
- **Page load times** - Monitor with Google PageSpeed Insights
- **Database performance** - Use Laravel Telescope (development only)
- **Error logging** - Check Laravel logs regularly
- **Server resources** - Monitor CPU, memory, and disk usage

### **Regular Maintenance**
```bash
# Weekly maintenance tasks
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Monthly tasks
php artisan migrate --force
composer update --no-dev
```

## 🆘 Troubleshooting

### **Common Issues and Solutions**

#### **1. 500 Internal Server Error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check file permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### **2. Database Connection Issues**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

#### **3. File Upload Issues**
```bash
# Check storage link
php artisan storage:link

# Check upload directory permissions
chmod -R 755 storage/app/public/
```

#### **4. Email Not Sending**
```bash
# Test email configuration
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

## 📞 Support Information

### **Aqeet Hub Support**
- **Control Panel:** Your Aqeet Hub dashboard
- **Documentation:** Aqeet Hub knowledge base
- **Support Ticket:** Submit through control panel

### **Project Support**
- **Documentation:** PROJECT_DOCUMENTATION.md
- **GitHub Repository:** Your project repository
- **Developer Contact:** Your contact information

## 🎯 Deployment Checklist

### **Pre-Deployment**
- [ ] Environment configured for production
- [ ] Database structure ready
- [ ] All features tested locally
- [ ] Security measures implemented
- [ ] Backup strategy prepared

### **Deployment**
- [ ] Files uploaded to server
- [ ] Database imported and configured
- [ ] Environment variables set
- [ ] File permissions configured
- [ ] SSL certificate installed

### **Post-Deployment**
- [ ] All user roles tested
- [ ] Core features verified
- [ ] Performance optimized
- [ ] Monitoring configured
- [ ] Backup system tested

---

**Deployment Guide Version:** 1.0  
**Last Updated:** December 2024  
**Compatible with:** Aqeet Hub, cPanel, Plesk, and other hosting platforms 