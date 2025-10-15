-- Medical Wholesale Management System - Database Setup Script
-- Run this script in your MySQL database to create the necessary databases

-- Create Master Database
CREATE DATABASE IF NOT EXISTS medwholesale_master CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create Sample Tenant Databases
CREATE DATABASE IF NOT EXISTS medwholesale_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS medwholesale_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant permissions (adjust username as needed)
-- GRANT ALL PRIVILEGES ON medwholesale_master.* TO 'root'@'localhost';
-- GRANT ALL PRIVILEGES ON medwholesale_demo.* TO 'root'@'localhost';
-- GRANT ALL PRIVILEGES ON medwholesale_test.* TO 'root'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show created databases
SHOW DATABASES LIKE 'medwholesale_%';
