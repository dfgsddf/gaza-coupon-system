# Gaza Coupon System - Graduation Project

## Overview
The Gaza Coupon System is a comprehensive web application built with Laravel that manages coupon distribution and redemption for charitable organizations. The system supports multiple user roles including Admin, Charity, Store, and Beneficiary, each with specific permissions and functionalities.

## Features

### 🔐 Multi-Role Authentication System
- **Admin**: Full system management, user management, statistics
- **Charity**: Campaign management, donation tracking, beneficiary approval
- **Store**: Coupon validation, transaction management, reporting
- **Beneficiary**: Request creation, coupon viewing, profile management

### 📊 Dashboard Features
- **Admin Dashboard**: User management, system statistics, activity monitoring
- **Charity Dashboard**: Campaign management, donation analytics, request approval
- **Store Dashboard**: Coupon validation, transaction history, revenue tracking
- **Beneficiary Dashboard**: Request status, available coupons, profile settings

### 🎫 Coupon Management
- Coupon generation and distribution
- QR code validation system
- Transaction tracking
- Redemption history

### 📈 Reporting & Analytics
- Real-time statistics
- Transaction reports
- Campaign performance metrics
- User activity monitoring

## Technology Stack
- **Backend**: Laravel 12.x (PHP 8.3+)
- **Frontend**: Bootstrap 5, jQuery, Blade Templates
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel's built-in authentication system
- **Authorization**: Custom role-based permission system

## Installation & Setup

### Prerequisites
- PHP 8.3 or higher
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx) or Laravel's built-in server

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd gaza-coupon-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gaza_coupon_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## Demo Credentials

### Development Environment
For demonstration purposes, you can use the Force Login links in the navigation bar (only visible in local/development environment):

- **Admin**: `/force-login`
- **Store**: `/force-login-store`
- **Beneficiary**: `/force-login-beneficiary`
- **Charity**: `/force-login-charity`

### Default Users (if seeded)
- **Admin**: admin@example.com / password
- **Store**: store@example.com / password
- **Beneficiary**: beneficiary@example.com / password
- **Charity**: charity@example.com / password

## Project Structure

```
gaza-coupon-system/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Http/Middleware/     # Custom middleware
│   └── Mail/               # Email notifications
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/views/       # Blade templates
├── routes/               # Application routes
└── public/              # Public assets
```

## Key Features Implementation

### Role-Based Access Control
- Custom middleware for each role (AdminMiddleware, StoreMiddleware, etc.)
- Permission-based access control using RolePermission model
- Automatic redirection based on user role

### Interactive Dashboards
- AJAX-powered real-time updates
- Dynamic statistics and charts
- User-friendly interface with Bootstrap 5

### Security Features
- CSRF protection on all forms
- Password hashing
- Input validation and sanitization
- SQL injection prevention through Eloquent ORM

## API Endpoints

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User management
- `PATCH /admin/users/{user}/status` - Update user status

### Store Routes
- `GET /store/dashboard` - Store dashboard
- `POST /store/validate-coupon` - Validate coupon
- `GET /store/transactions` - Transaction history

### Charity Routes
- `GET /charity/dashboard` - Charity dashboard
- `GET /charity/campaigns` - Campaign management
- `POST /charity/campaigns` - Create campaign

### Beneficiary Routes
- `GET /beneficiary/dashboard` - Beneficiary dashboard
- `GET /requests` - Request management
- `GET /coupons` - Available coupons

## Contributing

This is a graduation project. For any questions or issues, please contact the development team.

## License

This project is developed for educational purposes as a graduation project.

## Support

For technical support or questions about this graduation project, please contact the development team.

---

**Developed as a Graduation Project**  
*Gaza Coupon System - 2025*
