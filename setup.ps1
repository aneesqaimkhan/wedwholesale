# Medical Wholesale Management System - Windows Setup Script
# Run this script in PowerShell as Administrator

Write-Host "🏥 Medical Wholesale Management System Setup" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan

# Check if WAMP is running
Write-Host "`n📋 Checking WAMP status..." -ForegroundColor Yellow
$wampProcesses = Get-Process | Where-Object {$_.ProcessName -like "*apache*" -or $_.ProcessName -like "*mysql*"}
if ($wampProcesses.Count -eq 0) {
    Write-Host "⚠️  WAMP doesn't seem to be running. Please start WAMP first." -ForegroundColor Red
    Write-Host "   Make sure Apache and MySQL services are running." -ForegroundColor Red
    exit 1
} else {
    Write-Host "✅ WAMP is running" -ForegroundColor Green
}

# Check if MySQL is accessible
Write-Host "`n📋 Testing MySQL connection..." -ForegroundColor Yellow
try {
    $mysqlTest = mysql -u root -e "SELECT 1;" 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ MySQL connection successful" -ForegroundColor Green
    } else {
        Write-Host "❌ MySQL connection failed. Please check your MySQL configuration." -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "❌ MySQL command not found. Please ensure MySQL is in your PATH." -ForegroundColor Red
    exit 1
}

# Create databases
Write-Host "`n📋 Creating databases..." -ForegroundColor Yellow
mysql -u root -e "SOURCE setup.sql"
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Databases created successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to create databases" -ForegroundColor Red
    exit 1
}

# Run Laravel migrations
Write-Host "`n📋 Running Laravel migrations..." -ForegroundColor Yellow
php artisan migrate --database=master
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migrations completed successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Migrations failed" -ForegroundColor Red
    exit 1
}

# Run seeders
Write-Host "`n📋 Seeding database..." -ForegroundColor Yellow
php artisan db:seed --database=master
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Database seeded successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Database seeding failed" -ForegroundColor Red
    exit 1
}

# Update hosts file
Write-Host "`n📋 Updating hosts file..." -ForegroundColor Yellow
$hostsFile = "$env:SystemRoot\System32\drivers\etc\hosts"
$hostsEntries = @(
    "127.0.0.1 medwholesale.local",
    "127.0.0.1 demo.medwholesale.local",
    "127.0.0.1 test.medwholesale.local"
)

$currentHosts = Get-Content $hostsFile
$needsUpdate = $false

foreach ($entry in $hostsEntries) {
    if ($currentHosts -notcontains $entry) {
        $needsUpdate = $true
        break
    }
}

if ($needsUpdate) {
    try {
        Add-Content $hostsFile "`n# Medical Wholesale Management System"
        foreach ($entry in $hostsEntries) {
            Add-Content $hostsFile $entry
        }
        Write-Host "✅ Hosts file updated successfully" -ForegroundColor Green
    } catch {
        Write-Host "⚠️  Could not update hosts file automatically." -ForegroundColor Yellow
        Write-Host "   Please add these entries manually to your hosts file:" -ForegroundColor Yellow
        foreach ($entry in $hostsEntries) {
            Write-Host "   $entry" -ForegroundColor White
        }
    }
} else {
    Write-Host "✅ Hosts file already configured" -ForegroundColor Green
}

# Final instructions
Write-Host "`n🎉 Setup completed successfully!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Cyan

Write-Host "`n📝 Next Steps:" -ForegroundColor Yellow
Write-Host "1. Start the Laravel development server:" -ForegroundColor White
Write-Host "   php artisan serve --host=medwholesale.local --port=8000" -ForegroundColor Gray

Write-Host "`n2. Access the application:" -ForegroundColor White
Write-Host "   Main site: http://medwholesale.local:8000" -ForegroundColor Gray
Write-Host "   Demo tenant: http://demo.medwholesale.local:8000" -ForegroundColor Gray
Write-Host "   Test tenant: http://test.medwholesale.local:8000" -ForegroundColor Gray

Write-Host "`n3. Login credentials:" -ForegroundColor White
Write-Host "   Demo: admin@demomedical.com / password123" -ForegroundColor Gray
Write-Host "   Test: admin@testmedical.com / password123" -ForegroundColor Gray

Write-Host "`n4. If you can't access the subdomains:" -ForegroundColor White
Write-Host "   - Check that WAMP is running" -ForegroundColor Gray
Write-Host "   - Verify hosts file entries" -ForegroundColor Gray
Write-Host "   - Try accessing via localhost:8000" -ForegroundColor Gray

Write-Host "`n🚀 Happy coding!" -ForegroundColor Cyan
