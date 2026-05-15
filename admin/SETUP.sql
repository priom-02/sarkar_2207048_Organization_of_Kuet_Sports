-- SQL Setup Script for Admin Authentication
-- Run this in your phpMyAdmin or MySQL client to create the admins table

-- Create the 'admins' table if it doesn't exist
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert test admin user
-- Username: admin
-- Password: admin123 (hashed using bcrypt with cost 10)
-- To generate new password hashes, use the PHP code below or online tool
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$G5ci5HibhKksHphLuaAB7uysZbMLkkWJCHDeyiK1UFFxsoKPgrpJq', 'admin@kuet-sports.com');

-- Additional admin users (optional):
-- To add more admins, use this format (generate new password hashes):
-- INSERT INTO admins (username, password, email) VALUES 
-- ('user2', '[hashed_password]', 'user2@kuet-sports.com');

-- ========================================
-- IMPORTANT: How to Hash Passwords
-- ========================================
-- In PHP, use: password_hash("yourpassword", PASSWORD_BCRYPT)
-- 
-- Example in PHP command line:
-- php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
--
-- For testing, the test user credentials are:
-- Username: admin
-- Password: admin123
--
-- Change password after first login!
-- ========================================
