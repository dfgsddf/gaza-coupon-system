# 🚀 How to Deploy Gaza Coupon System to Aqeet Hub

## 📋 **Prerequisites**
- Aqeet Hub hosting account
- Domain name (or subdomain)
- PHP 8.3+ support
- MySQL 8.0+ database

## 🎯 **Step-by-Step Deployment Process**

### **Step 1: Prepare Your Project Files**

#### **Option A: Manual File Selection (Recommended)**
1. **Open File Explorer** and navigate to your project folder
2. **Select these folders and files:**
   ```
   ✅ app/                    (Application logic)
   ✅ bootstrap/             (Framework bootstrap)
   ✅ config/               (Configuration files)
   ✅ database/             (Migrations and seeders)
   ✅ public/               (Web root - IMPORTANT!)
   ✅ resources/            (Views and assets)
   ✅ routes/               (URL routing)
   ✅ storage/              (File storage)
   ✅ vendor/               (Dependencies)
   ✅ .env.example          (Environment template)
   ✅ artisan               (Laravel command tool)
   ✅ composer.json         (Dependencies list)
   ✅ composer.lock         (Locked dependencies)
   ```

3. **Right-click** → **Send to** → **Compressed (zipped) folder**
4. **Rename** the zip file to `gaza-coupon-system.zip`

#### **Option B: Using PowerShell (Alternative)**
```powershell
# Stop the development server first
# Then run this command:
Compress-Archive -Path app,bootstrap,config,database,public,resources,routes,storage,vendor,artisan,composer.json,composer.lock,.env.example -DestinationPath 'gaza-coupon-system.zip' -Force
```

### **Step 2: Aqeet Hub Account Setup**

1. **Login to Aqeet Hub** control panel
2. **Create a new domain** or use existing one
3. **Ensure PHP 8.3+** is enabled
4. **Create MySQL database:**
   - Database name: `gaza_coupon_db`
   - Username: `gaza_user`
   - Password: `your_secure_password`
   - Host: `localhost`

### **Step 3: Upload Files to Aqeet Hub**

1. **Access File Manager** in Aqeet Hub
2. **Navigate to your domain directory** (usually `public_html/`)
3. **Upload** the `gaza-coupon-system.zip` file
4. **Extract** the zip file to the domain root
5. **Verify** all files are in the correct location

### **Step 4: Database Configuration**

1. **Access phpMyAdmin** in Aqeet Hub
2. **Select your database** (`gaza_coupon_db`)
3. **Import database structure** (if needed)

### **Step 5: Environment Configuration**

1. **Rename** `.env.example` to `.env`
2. **Edit** the `.env` file with your settings:

```env
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
DB_DATABASE=gaza_coupon_db
DB_USERNAME=gaza_user
DB_PASSWORD=your_secure_password

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
```

### **Step 6: Application Setup via SSH/Terminal**

1. **Access SSH Terminal** in Aqeet Hub
2. **Navigate to your project directory:**
   ```bash
   cd /path/to/your/domain
   ```

3. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

4. **Run database migrations:**
   ```bash
   php artisan migrate --force
   ```

5. **Seed the database with sample data:**
   ```bash
   php artisan db:seed --force
   ```

### **Step 7: File Permissions**

1. **Set proper file permissions:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   chmod -R 755 public/
   ```

2. **Create storage link:**
   ```bash
   php artisan storage:link
   ```

### **Step 8: Production Optimization**

1. **Clear all caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Cache for production:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### **Step 9: Domain Configuration**

1. **Ensure your domain points** to the `public/` folder
2. **Set up SSL certificate** (if not already done)
3. **Test your domain** in browser

## 🔑 **Default Login Credentials**

After deployment, you can login with these accounts:

### **Admin Account:**
- **Email:** admin@gazacoupon.com
- **Password:** password123
- **URL:** https://your-domain.aqeethub.com/admin/dashboard

### **Charity Account:**
- **Email:** charity@gazacoupon.com
- **Password:** password123
- **URL:** https://your-domain.aqeethub.com/charity/dashboard

### **Store Account:**
- **Email:** store@gazacoupon.com
- **Password:** password123
- **URL:** https://your-domain.aqeethub.com/store/dashboard

### **Beneficiary Account:**
- **Email:** beneficiary@gazacoupon.com
- **Password:** password123
- **URL:** https://your-domain.aqeethub.com/beneficiary/dashboard

## 🎓 **For Your Graduation Presentation**

### **Perfect Demo Flow:**
1. **Show live website** at your Aqeet Hub domain
2. **Demonstrate Force Login** feature for easy role switching
3. **Show Admin Dashboard** with system statistics
4. **Show Charity Dashboard** with campaigns and requests
5. **Show Store Dashboard** with transactions
6. **Show Beneficiary Dashboard** with requests and coupons
7. **Explain technical architecture** using PROJECT_DOCUMENTATION.md

### **Key Features to Highlight:**
- ✅ **Multi-role authentication** system
- ✅ **Interactive dashboards** with real-time data
- ✅ **Campaign management** workflow
- ✅ **Request processing** system
- ✅ **Coupon generation** and redemption
- ✅ **Comprehensive reporting** with charts
- ✅ **Settings management** for all roles

## 🆘 **Troubleshooting Common Issues**

### **Issue 1: 500 Internal Server Error**
**Solution:**
- Check file permissions (755 for directories)
- Verify .env file exists and is configured
- Check Laravel logs in storage/logs/

### **Issue 2: Database Connection Error**
**Solution:**
- Verify database credentials in .env
- Ensure database exists and is accessible
- Check MySQL service is running

### **Issue 3: Page Not Found (404)**
**Solution:**
- Ensure .htaccess file is in public/ directory
- Check Apache mod_rewrite is enabled
- Verify domain points to correct directory

### **Issue 4: White Screen**
**Solution:**
- Check PHP error logs
- Verify APP_DEBUG=true temporarily
- Check file permissions

## 📞 **Aqeet Hub Support**

### **Getting Help:**
1. **Aqeet Hub Documentation** - Check their knowledge base
2. **Support Ticket** - Submit through control panel
3. **Live Chat** - If available in your plan
4. **Community Forums** - For general hosting questions

### **Important Aqeet Hub Settings:**
- **PHP Version:** 8.3 or higher
- **MySQL Version:** 8.0 or higher
- **Memory Limit:** 256M or higher
- **Max Execution Time:** 300 seconds
- **Upload Max Filesize:** 64M

## ✅ **Deployment Checklist**

### **Pre-Deployment:**
- [ ] Project fully tested locally
- [ ] All features working correctly
- [ ] Database seeded with sample data
- [ ] Documentation completed

### **Deployment:**
- [ ] Files uploaded to Aqeet Hub
- [ ] Database created and configured
- [ ] Environment file set up correctly
- [ ] Application key generated
- [ ] Migrations and seeding completed
- [ ] File permissions set correctly
- [ ] Production optimization applied

### **Post-Deployment:**
- [ ] All user roles tested
- [ ] All features working
- [ ] Email functionality tested
- [ ] Performance verified
- [ ] Security measures confirmed

## 🎯 **Success!**

Once you complete these steps, your Gaza Coupon System will be live at:
**https://your-domain.aqeethub.com**

**Your graduation project will be accessible worldwide and ready for presentation!** 🚀

---

**Deployment Guide Version:** 1.0  
**Last Updated:** December 2024  
**Compatible with:** Aqeet Hub hosting platform 