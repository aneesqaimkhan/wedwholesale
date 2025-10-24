# Tenant Authentication System

## Overview
Your multi-tenant Laravel application now includes a complete user authentication system for each tenant. Each tenant has their own isolated user database with login and registration functionality.

## Features

### User Management
- **User Registration**: Users can register with name, email, password, phone, company, and address
- **User Login**: Secure authentication with remember me functionality
- **User Roles**: Admin, User, Manager roles available
- **User Status**: Active/Inactive user status
- **Dashboard**: Personalized dashboard showing user information

### Database Structure
Each tenant database includes:
- `users` table with extended fields:
  - `name`, `email`, `password` (standard Laravel auth)
  - `phone`, `company`, `address` (additional profile fields)
  - `role` (admin, user, manager)
  - `is_active` (boolean status)

## Commands

### Create a Tenant with Authentication
```bash
# Create tenant and run migrations
php artisan tenant:create-simple "Company Name" "subdomain"

# Create a test user for the tenant
php artisan tenant:create-user "subdomain" "User Name" "email@example.com" "password"
```

### Example:
```bash
# Create tenant
php artisan tenant:create-simple "Acme Corp" "acme"

# Create admin user
php artisan tenant:create-user "acme" "John Admin" "admin@acme.com" "password123"
```

## URLs and Access

### Tenant URLs
- **Main tenant page**: `http://{subdomain}.localhost/webwholesale/`
- **Login**: `http://{subdomain}.localhost/webwholesale/login`
- **Register**: `http://{subdomain}.localhost/webwholesale/register`
- **Dashboard**: `http://{subdomain}.localhost/webwholesale/dashboard` (requires login)

### Example for "demo" tenant:
- `http://demo.localhost/webwholesale/` - Main page
- `http://demo.localhost/webwholesale/login` - Login form
- `http://demo.localhost/webwholesale/register` - Registration form
- `http://demo.localhost/webwholesale/dashboard` - User dashboard

## Authentication Flow

### 1. User Registration
1. User visits `{subdomain}.localhost/register`
2. Fills out registration form
3. Account is created in tenant's database
4. User is automatically logged in
5. Redirected to dashboard

### 2. User Login
1. User visits `{subdomain}.localhost/login`
2. Enters email and password
3. System authenticates against tenant's database
4. User is logged in and redirected to dashboard

### 3. Dashboard Access
1. User must be authenticated
2. Dashboard shows user profile information
3. User can logout from dashboard

## Security Features

- **Password Hashing**: All passwords are securely hashed
- **CSRF Protection**: All forms include CSRF tokens
- **Session Management**: Secure session handling
- **Input Validation**: All inputs are validated
- **Database Isolation**: Each tenant's users are completely isolated

## User Roles

- **Admin**: Full access (can be assigned via command)
- **User**: Standard user access (default for registration)
- **Manager**: Manager level access

## Testing the System

### 1. Create a Test Tenant
```bash
php artisan tenant:create-simple "Test Company" "test"
```

### 2. Create a Test User
```bash
php artisan tenant:create-user "test" "Test User" "test@test.com" "password123"
```

### 3. Test Access
1. Visit `http://test.localhost/webwholesale/`
2. Click on login URL
3. Login with: `test@test.com` / `password123`
4. Access dashboard

### 4. Test Registration
1. Visit `http://test.localhost/webwholesale/register`
2. Fill out registration form
3. New user is created and logged in

## Customization

### Adding New User Fields
1. Create migration: `php artisan make:migration add_field_to_users_table`
2. Update User model's `$fillable` array
3. Update registration form and dashboard views
4. Run migration: `php artisan tenant:migrate {subdomain}`

### Customizing Views
- Login form: `resources/views/tenant/auth/login.blade.php`
- Registration form: `resources/views/tenant/auth/register.blade.php`
- Dashboard: `resources/views/tenant/dashboard.blade.php`
- Layout: `resources/views/tenant/layouts/app.blade.php`

### Adding New Routes
Add protected routes in `routes/web.php` within the tenant domain group:
```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/settings', [AuthController::class, 'settings']);
});
```

## Production Considerations

1. **SSL Certificates**: Set up SSL for subdomains
2. **Email Verification**: Add email verification for registrations
3. **Password Reset**: Implement password reset functionality
4. **Rate Limiting**: Add rate limiting to login/register forms
5. **Logging**: Add authentication logging
6. **Backup**: Regular backups of tenant databases

## Troubleshooting

### Common Issues
1. **User not found**: Check if user exists in correct tenant database
2. **Login fails**: Verify password and email are correct
3. **Database connection**: Ensure tenant database is accessible
4. **Session issues**: Clear application cache and sessions

### Useful Commands
```bash
# Check tenant list
php artisan tenant:list

# Run migrations for specific tenant
php artisan tenant:migrate {subdomain}

# Create user for tenant
php artisan tenant:create-user {subdomain} {name} {email} {password}

# Clear cache
php artisan cache:clear
php artisan config:clear
```
