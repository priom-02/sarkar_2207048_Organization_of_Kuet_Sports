-- SQL Setup Script for User Authentication System
-- Run this in your phpMyAdmin or MySQL client to create the users table

-- Create the 'users' table for member login system
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    team VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create index for faster email lookups
CREATE INDEX idx_email ON users(email);

-- ========================================
-- IMPORTANT: Password Hashing
-- ========================================
-- All passwords are stored using bcrypt hashing
-- Do NOT store plain text passwords!
--
-- In PHP, use: password_hash("yourpassword", PASSWORD_BCRYPT)
-- To verify: password_verify("entered_password", $stored_hash)
--
-- For testing, use the generate_hash.php file or:
-- php -r "echo password_hash('password123', PASSWORD_BCRYPT);"
-- ========================================
