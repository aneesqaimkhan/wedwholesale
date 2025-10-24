# Simple Deployment Guide

## Quick Setup for Live Server

### Step 1: Update Live Configuration
Edit `config/deployment.php` and update the 'live' section:

```php
'live' => [
    'domain' => 'yourdomain.com',                    // Your main domain
    'subdomain_pattern' => '{subdomain}.yourdomain.com', // Subdomain pattern
    'protocol' => 'https',                           // http or https
    'subdirectory' => '/webwholesale',                // Your app subdirectory
],
```

**Example for your domain:**
```php
'live' => [
    'domain' => 'mycompany.com',
    'subdomain_pattern' => '{subdomain}.mycompany.com',
    'protocol' => 'https',
    'subdirectory' => '/webwholesale',
],
```

### Step 2: Update .env File
Create/update your `.env` file on the live server:

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com/webwholesale

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_live_database
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### Step 3: Run Commands on Live Server
```bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Step 4: Set Up Web Server
Configure your web server (Apache/Nginx) to:
1. Point to your `/webwholesale` directory
2. Handle subdomains with wildcard DNS
3. Set up SSL certificates for subdomains

### Step 5: Create Your First Tenant
```bash
# Create a tenant
php artisan tenant:create-simple "Your Company" "yourcompany"

# Create an admin user
php artisan tenant:create-user "yourcompany" "Admin User" "admin@yourcompany.com" "password123"
```

## That's It! 🎉

Your multi-tenant application is now ready on the live server.

### URLs:
- **Main app**: `https://yourdomain.com/webwholesale/`
- **Tenant**: `https://yourcompany.yourdomain.com/webwholesale/`

### What You Need to Change for Live:
1. **Only 1 file**: `config/deployment.php` - Update the 'live' section
2. **Only 1 file**: `.env` - Set your database credentials and domain
3. **Run 4 commands**: composer install, key:generate, migrate, clear caches

### No Code Changes Needed!
The application automatically detects if it's running on localhost (local) or your domain (live) and adjusts URLs accordingly.

## Troubleshooting

### If URLs are wrong:
1. Check `config/deployment.php` has correct domain
2. Run `php artisan config:clear`
3. Check `.env` has correct `APP_URL`

### If subdomains don't work:
1. Set up wildcard DNS: `*.yourdomain.com` → your server IP
2. Configure web server for subdomains
3. Test with: `https://test.yourdomain.com/webwholesale/`

### If database issues:
1. Check `.env` database credentials
2. Run `php artisan migrate`
3. Create tenant: `php artisan tenant:create-simple "Test" "test"`
