# Simple Multi-Tenant Setup

## Overview
This is a simple multi-tenant Laravel application where:
- **Master Database**: Stores tenant information and credentials
- **Tenant Databases**: Each tenant has their own separate database
- **Subdomain Identification**: Each tenant is identified by subdomain

## How It Works

### 1. Master Database Structure
The master database contains a `tenants` table with:
- `name` - Tenant company name
- `subdomain` - Unique subdomain (e.g., "test", "company1")
- `database_name` - Tenant's database name
- `database_host` - Database host (usually 127.0.0.1)
- `database_username` - Database username for tenant
- `database_password` - Database password for tenant
- `database_port` - Database port (usually 3306)
- `is_active` - Whether tenant is active

### 2. Request Flow
1. User visits `test.localhost`
2. Middleware extracts subdomain "test"
3. Connects to master database to find tenant with subdomain "test"
4. Switches to tenant's database using stored credentials
5. All subsequent database operations use tenant's database

## Commands

### Create a New Tenant
```bash
php artisan tenant:create "Company Name" "subdomain" "database_name" "db_username" "db_password"
```

Example:
```bash
php artisan tenant:create "Acme Corp" "acme" "tenant_acme_db" "acme_user" "acme_password"
```

### List All Tenants
```bash
php artisan tenant:list
```

### Run Migrations for Specific Tenant
```bash
php artisan tenant:migrate {subdomain}
```

Example:
```bash
php artisan tenant:migrate acme
```

## Configuration

### Database Connections
- **Master**: Uses your main database connection (from .env)
- **Tenant**: Dynamically configured per request

### Middleware
- `IdentifyTenant` middleware runs on every request
- Automatically switches database based on subdomain

## Testing

### 1. Create a Tenant
```bash
php artisan tenant:create "Test Company" "test" "tenant_test_db" "test_user" "test_password"
```

### 2. Run Migrations for Tenant
```bash
php artisan tenant:migrate test
```

### 3. Test URLs
- Main app: `http://localhost/webwholesale/`
- Tenant: `http://test.localhost/webwholesale/`

## Simple Architecture

```
Master Database (your main DB)
├── tenants table
│   ├── id, name, subdomain
│   ├── database_name, database_host
│   ├── database_username, database_password
│   └── is_active
└── Other main app tables

Tenant 1 Database (tenant_test_db)
├── All Laravel tables
├── Users, products, orders, etc.
└── Completely isolated data

Tenant 2 Database (tenant_acme_db)
├── All Laravel tables
├── Users, products, orders, etc.
└── Completely isolated data
```

## Benefits of This Approach

1. **Simple**: No complex packages, just basic Laravel
2. **Flexible**: Each tenant can have different database credentials
3. **Secure**: Complete data isolation between tenants
4. **Scalable**: Easy to add new tenants
5. **Understandable**: Clear code flow and logic

## Environment Setup

Make sure your `.env` file has your master database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_master_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Production Deployment

1. Set up wildcard DNS for your domain
2. Configure web server for subdomains
3. Ensure all tenant databases are created
4. Run migrations for each tenant
5. Set up SSL certificates for subdomains
