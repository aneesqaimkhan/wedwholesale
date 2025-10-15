# Medical Wholesale Management System

A multi-tenant wholesale management system built with Laravel and MySQL, specifically designed for the medical industry.

## Features

- **Multi-tenant Architecture**: Each customer operates from a separate subdomain with their own database
- **Secure Authentication**: Login attempt limitations, password hashing, and account locking
- **Tenant Management**: Global configuration and tenant-specific settings
- **Dashboard**: Modern, responsive dashboard with module placeholders
- **Database Isolation**: Complete data separation between tenants

## Architecture

### Database Structure
- **Master Database**: Contains tenant information and global configuration
- **Tenant Databases**: Separate database per tenant containing their operational data

### Multi-tenant Flow
1. User accesses subdomain (e.g., `demo.medwholesale.local`)
2. System detects tenant from subdomain
3. System configures database connection for that tenant
4. User authenticates against master database
5. System redirects to tenant-specific dashboard

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- WAMP (for local development)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd medwholesale
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Update database configuration for WAMP:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=medwholesale_master
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Create Master Database**
   ```bash
   # Create the master database in MySQL
   CREATE DATABASE medwholesale_master;
   ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate --database=master
   php artisan db:seed --database=master
   ```

7. **Create Sample Tenant Databases**
   ```bash
   # Create tenant databases
   CREATE DATABASE medwholesale_demo;
   CREATE DATABASE medwholesale_test;
   ```

8. **Configure Local Development**
   - Add to your hosts file (`C:\Windows\System32\drivers\etc\hosts`):
     ```
     127.0.0.1 medwholesale.local
     127.0.0.1 demo.medwholesale.local
     127.0.0.1 test.medwholesale.local
     ```
   - Configure WAMP virtual hosts or use Laravel Valet

9. **Start the Application**
   ```bash
   php artisan serve --host=medwholesale.local --port=8000
   ```

## Usage

### Sample Tenants
The system comes with two sample tenants:

1. **Demo Tenant**
   - URL: `http://demo.medwholesale.local:8000`
   - Email: `admin@demomedical.com`
   - Password: `password123`

2. **Test Tenant**
   - URL: `http://test.medwholesale.local:8000`
   - Email: `admin@testmedical.com`
   - Password: `password123`

### Login Process
1. Navigate to a tenant subdomain
2. Enter your email and password
3. System authenticates against master database
4. Upon success, you're redirected to the tenant dashboard

## Security Features

- **Login Attempt Limitation**: Maximum 3 failed attempts before account lockout
- **Account Lockout**: 30-minute lockout after failed attempts
- **Password Hashing**: Uses Laravel's bcrypt for secure password storage
- **Rate Limiting**: IP-based rate limiting for login attempts
- **Session Security**: Secure session handling with regeneration

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── TenantAuthController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── TenantDetection.php
├── Models/
│   ├── MasterUser.php
│   └── Tenant.php
database/
├── migrations/
│   ├── create_tenants_table.php
│   └── create_master_users_table.php
└── seeders/
    └── MasterDatabaseSeeder.php
resources/
└── views/
    ├── auth/
    │   └── login.blade.php
    ├── errors/
    │   ├── tenant-not-found.blade.php
    │   └── tenant-inactive.blade.php
    └── dashboard.blade.php
```

## Next Steps

The foundation is now set up for building the business modules:

1. **Products Module**: Inventory management, categories, pricing
2. **Customers Module**: Customer database, sales history
3. **Suppliers Module**: Supplier management, purchase orders
4. **Sales Module**: Sales orders, invoicing, payments
5. **Purchase Module**: Purchase orders, receipts, vendor management
6. **Reports Module**: Analytics, reporting, business intelligence

## Development Notes

- All tenant-specific data is stored in separate databases
- Authentication is handled through the master database
- Middleware automatically detects and configures tenant connections
- Views are shared across tenants but data is isolated
- Each tenant can have different settings and configurations

## License

This project is proprietary software for medical wholesale management.