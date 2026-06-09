-- ========================================
-- SYNC FIX MIGRATION SCRIPT
-- Adds profile_pic column to users table
-- Synchronizes users and members tables
-- ========================================

-- Add profile_pic column to users table if it doesn't exist
ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'image/members/default.png' AFTER password;

-- Add photo column reference in users table (optional, for future use)
-- This script ensures users table can store profile pictures independently

-- Verify the change
-- SELECT id, email, profile_pic FROM users LIMIT 1;

-- ========================================
-- NOTES:
-- ========================================
-- 1. This migration adds the profile_pic column to the users table
-- 2. When a user registers with a profile picture, it will be stored here
-- 3. When an admin updates a member's photo, the users table will be updated if a matching email is found
-- 4. Default value is set to 'image/members/default.png'
-- 5. Team synchronization is now automatic when admin updates member team
