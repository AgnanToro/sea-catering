# SEA Catering - Healthy Meals Delivery Platform

![SEA Catering](https://img.shields.io/badge/CodeIgniter-4.6.1-orange) ![PHP](https://img.shields.io/badge/PHP-8.2+-blue) ![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue) ![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.0+-green)

**SEA Catering** adalah platform web untuk layanan pesan-antar makanan sehat dengan sistem subscription berbasis CodeIgniter 4. Platform ini menyediakan berbagai paket makanan sehat dengan manajemen admin yang komprehensif.

## 🚀 Technical Challenge Requirements

Proyek ini memenuhi semua requirement **SEA Technical Challenge Level 1-5**:

### ✅ Level 1: Basic CRUD Operations
- **Users Management**: Register, login, profile management
- **Subscriptions Management**: Create, read, update, delete subscriptions  
- **Contact Messages**: Send and manage contact messages
- **Menu Management**: Display food menus and packages

### ✅ Level 2: Database Design & Relationships
- **Users Table**: User authentication and profile data
- **Subscriptions Table**: Subscription plans and user relationships
- **Contact Messages Table**: Customer inquiries and support
- **Proper Foreign Key Relationships**: User-Subscription relationships

### ✅ Level 3: Authentication & Authorization
- **Session-based Authentication**: Secure login/logout system
- **Role-based Access Control**: User and Admin roles
- **Protected Routes**: Admin-only areas and user dashboards
- **Input Validation**: Server-side validation for all forms

### ✅ Level 4: Security Implementation
- **XSS Protection**: Input sanitization and output escaping
- **SQL Injection Prevention**: Parameterized queries and ORM
- **CSRF Protection**: Built-in CodeIgniter CSRF tokens
- **Strong Password Policy**: Complex password requirements with real-time validation
- **Session Security**: Secure session management

### ✅ Level 5: Advanced Features
- **Admin Dashboard**: Comprehensive analytics and management
- **Data Visualization**: Charts for subscription growth and revenue
- **Export Functionality**: CSV data export for admins
- **Report Generation**: Business reports with statistics
- **Real-time Calculations**: Dynamic pricing and subscription management

## 🛠️ Technology Stack

- **Backend**: CodeIgniter 4.6.1 (PHP Framework)
- **Frontend**: HTML5, Tailwind CSS 3.x, Alpine.js 3.x
- **Database**: MySQL 8.0+
- **Charts**: Chart.js for data visualization
- **Icons**: Font Awesome 6.0
- **Server**: Apache/Nginx with PHP 8.2+

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.2 or higher** with extensions:
  - `intl`
  - `mbstring`
  - `mysqli` or `pdo_mysql`
  - `curl`
  - `gd` or `imagick`
  - `json`
  - `xml`
- **MySQL 8.0 or higher**
- **Composer** (PHP package manager)
- **Web Server** (Apache/Nginx) or PHP built-in server
- **Git** for version control

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/agnankun18/sea-catering.git
cd sea-catering
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the environment file and configure your settings:

```bash
copy env .env
```

Edit `.env` file with your configuration:

```env
#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080'
app.appTimezone = 'Asia/Jakarta'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = sea_apps_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------
# Generate new key using: php spark key:generate
encryption.key = your-32-character-secret-key

#--------------------------------------------------------------------
# SESSION
#--------------------------------------------------------------------
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
app.sessionCookieName = 'ci_session'
app.sessionExpiration = 7200
app.sessionSavePath = NULL
app.sessionMatchIP = false
app.sessionTimeToUpdate = 300
app.sessionRegenerateDestroy = false

#--------------------------------------------------------------------
# CSRF
#--------------------------------------------------------------------
security.csrfProtection = 'session'
security.tokenRandomize = true
security.tokenName = 'csrf_token_name'
security.headerName = 'X-CSRF-TOKEN'
security.cookieName = 'csrf_cookie_name'
security.expires = 7200
security.regenerate = true
```

### 4. Database Setup

Create a MySQL database:

```sql
CREATE DATABASE sea_apps_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run the migrations to create tables:

```bash
php spark migrate
```

Seed the database with sample data:

```bash
php spark db:seed DatabaseSeeder
```

### 5. Generate Application Key

```bash
php spark key:generate
```

### 6. Set Permissions

Ensure the writable directory has proper permissions:

```bash
# On Linux/Mac
chmod -R 775 writable/
chown -R www-data:www-data writable/

# On Windows (run as Administrator)
icacls writable /grant Users:F /t
```

### 7. Start the Development Server

```bash
php spark serve --host=localhost --port=8080
```

Your application will be available at: `http://localhost:8080`

## 👤 Admin Account Setup

### Method 1: Database Seeder (Recommended)

The database seeder automatically creates an admin account:

```
Email: admin@seaapps.com
Password: Admin123!

## User account
email:agnankun18@gmail.com
password :noel123!
```

### Method 2: Manual Database Insert

If you need to create additional admin accounts, run this SQL:

```sql
INSERT INTO users (name, email, phone, password, is_admin, email_verified_at, created_at, updated_at) 
VALUES (
    'Admin Name', 
    'admin@yourdomain.com', 
    '081234567890',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    1,
    NOW(),
    NOW(),
    NOW()
);
```

### Method 3: Registration + Manual Database Update

1. Register a normal account through `/register`
2. Update the `is_admin` field in the database:

```sql
UPDATE users SET is_admin = 1 WHERE email = 'agnankun18@gmail.com';
```

## 🎯 Application Features

### 🏠 **Public Features**
- **Homepage**: Company overview and service introduction
- **Menu Catalog**: Browse available meal packages (Diet, Protein, Royal)
- **Subscription Plans**: View and subscribe to meal plans
- **Contact Form**: Send inquiries to customer service
- **User Registration**: Create new user accounts
- **User Login**: Secure authentication system

### 👤 **User Dashboard**
- **Profile Management**: Update personal information
- **Subscription History**: View active and past subscriptions
- **Subscription Details**: Manage delivery preferences and meal types
- **Account Settings**: Change password and preferences

### 🛡️ **Admin Dashboard**
- **Analytics Overview**: Key metrics and statistics
- **Subscription Management**: View, edit, and delete subscriptions
- **User Management**: Monitor registered users
- **Contact Messages**: Review and respond to customer inquiries
- **Data Export**: Export data to CSV format
- **Report Generation**: Generate business reports
- **Growth Charts**: Visual data representation with Chart.js

### 🔒 **Security Features**
- **Session-based Authentication**: Secure login/logout
- **CSRF Protection**: Cross-site request forgery prevention
- **XSS Protection**: Input sanitization and output escaping
- **SQL Injection Prevention**: Parameterized queries
- **Password Security**: Strong password requirements
- **Role-based Access**: Admin and user privilege separation

## 📁 Project Structure

```
sea-catering/
├── app/
│   ├── Controllers/        # Application controllers
│   │   ├── Api/           # API controllers
│   │   └── WebController.php
│   ├── Models/            # Data models
│   ├── Views/             # View templates
│   │   ├── layout/        # Layout templates
│   │   └── pages/         # Page templates
│   ├── Filters/           # Authentication filters
│   ├── Config/            # Configuration files
│   └── Database/          # Migrations and seeds
├── public/                # Public assets
├── writable/              # Logs and cache
├── vendor/                # Composer dependencies
├── .env                   # Environment configuration
├── composer.json          # PHP dependencies
└── README.md             # This file
```

## 🌐 API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `GET /logout` - User logout

### Subscriptions
- `GET /api/subscriptions` - List subscriptions (Admin)
- `POST /api/subscriptions` - Create subscription
- `GET /api/subscriptions/{id}` - Get subscription details
- `DELETE /api/subscriptions/{id}` - Delete subscription (Admin)
- `PATCH /api/subscriptions/{id}/status` - Update status (Admin)

### Contact
- `POST /api/contact` - Send contact message
- `GET /api/contact` - List contact messages (Admin)

### Admin
- `GET /admin/export` - Export data to CSV
- `POST /admin/report` - Generate business report

## 🧪 Testing

### Manual Testing
Access the test pages:
- Contact Form Test: `http://localhost:8080/test_contact_form.html`
- Quick Actions Test: `http://localhost:8080/test_quick_actions_advanced.html`

### Admin Login Test
Use the test endpoint to create admin session:
```
GET http://localhost:8080/test/admin-login
```

## 🚀 Deployment

### Production Environment

1. **Web Server Configuration**
   - Point document root to `public/` directory
   - Enable URL rewriting (mod_rewrite for Apache)
   - Set proper file permissions

2. **Environment Settings**
   ```env
   CI_ENVIRONMENT = production
   app.baseURL = 'https://yourdomain.com'
   ```

3. **Security Checklist**
   - Remove test endpoints
   - Set strong encryption key
   - Configure HTTPS
   - Set proper file permissions
   - Configure database backup

### Server Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher  
- **Memory**: Minimum 256MB
- **Disk Space**: Minimum 100MB

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check MySQL service is running
- Verify database credentials in `.env`
- Ensure database exists

**Permission Denied**
- Set proper permissions on `writable/` directory
- Check file ownership

**Session Issues**
- Clear browser cookies
- Check session configuration in `.env`
- Verify `writable/session/` permissions

**404 Errors**
- Enable URL rewriting on web server
- Check `.htaccess` file in public directory

## 📞 Support

For support and questions:
- **Email**: agnankun18@gmail.com
- **Documentation**: Check this README
- **Issues**: Create an issue on GitHub

---

**Built with ❤️ for SEA Technical Challenge**

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
