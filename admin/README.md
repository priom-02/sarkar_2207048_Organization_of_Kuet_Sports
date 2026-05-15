# Admin Panel Setup & Usage Guide

## 📋 Overview

This is a secure admin authentication system for the KUET Sports Organization website with:
- **Login page** with form validation
- **Session-based authentication** 
- **Password hashing** (bcrypt)
- **SQL injection prevention** (prepared statements)
- **Protected dashboard** that requires login
- **Secure logout** that destroys sessions

## 🚀 Quick Start

### Step 1: Run SQL Setup

1. Open **phpMyAdmin** (usually at `http://localhost/phpmyadmin`)
2. Select your `kuet_sports` database
3. Go to the **SQL** tab
4. Copy and paste the contents of `SETUP.sql`
5. Click **Execute**

This will create the `admins` table and insert a test user:
- **Username:** `admin`
- **Password:** `admin123`

### Step 2: Access Admin Panel

1. Open your browser and go to: `http://localhost/admin/`
2. You'll be redirected to the login page
3. Enter the test credentials:
   - Username: `admin`
   - Password: `admin123`
4. Click **Login**

### Step 3: Verify

After successful login:
- ✅ You should see the **Admin Dashboard**
- ✅ Your username appears in the top-right corner
- ✅ Click **Logout** to test session destruction
- ✅ Verify you're redirected to login page

## 📁 File Structure

```
admin/
├── index.php                 # Redirects to login.php
├── login.php                 # Secure login form
├── dashboard.php             # Protected admin dashboard
├── logout.php                # Session destruction
├── .htaccess                 # Security rules (Apache)
├── SETUP.sql                 # Database setup script
├── includes/
│   └── db.php               # Database connection
└── README.md                # This file
```

## 🔐 Security Features

### 1. **Password Hashing (Bcrypt)**
- Passwords are stored as bcrypt hashes, not plaintext
- `password_verify()` validates hashes securely
- Cost factor: 10 (secure but reasonably fast)

### 2. **SQL Injection Prevention**
- Uses `mysqli_prepare()` and parameterized queries
- User input is never directly interpolated into SQL

### 3. **Session Security**
- Sessions are server-side (not vulnerable to client-side manipulation)
- Unset $_SESSION variables on logout
- `session_destroy()` clears all session data

### 4. **Input Validation & Sanitization**
- Username/password checked for empty values
- Form output escaped with `htmlspecialchars()`
- Generic error messages (don't reveal if user exists)

### 5. **Directory Protection (.htaccess)**
- Prevents direct access to `/admin/includes/` folder
- Disables directory listing
- Sets security headers (X-Content-Type-Options, X-Frame-Options, etc.)

## 👤 Managing Admin Users

### Add a New Admin

1. Generate a bcrypt hash for the password:
   ```php
   // Use PHP command line or create a temp file
   php -r "echo password_hash('newpassword', PASSWORD_BCRYPT);"
   ```
   Output example: `$2y$10$abc123...xyz789`

2. Insert into database:
   ```sql
   INSERT INTO admins (username, password, email) VALUES 
   ('newadmin', '$2y$10$abc123...xyz789', 'newadmin@kuet-sports.com');
   ```

### Change Admin Password

1. Generate new bcrypt hash (see above)
2. Update the database:
   ```sql
   UPDATE admins 
   SET password = '$2y$10$newhashedpassword...' 
   WHERE username = 'admin';
   ```

### Delete an Admin

```sql
DELETE FROM admins WHERE username = 'username_to_delete';
```

## 🧪 Testing Checklist

- [ ] Login with correct credentials → redirects to dashboard
- [ ] Login with wrong password → shows error message
- [ ] Login with non-existent username → shows generic error
- [ ] Access dashboard without login → redirected to login.php
- [ ] Session persists after page refresh
- [ ] Click Logout → session destroyed → login.php shown
- [ ] Try SQL injection (`' OR '1'='1`) → login fails (prepared statement)
- [ ] Check browser console → no sensitive errors exposed

## 🔧 Configuration

### Database Connection (admin/includes/db.php)

```php
$servername = "localhost";
$username = "root";           // MySQL username
$password = "";               // MySQL password
$database = "kuet_sports";    // Database name
```

**Change these if your database credentials are different.**

### Session Settings

To further secure sessions, add to your `.htaccess` or `php.ini`:

```php
session.cookie_httponly = 1      # Prevent JavaScript access
session.cookie_secure = 1        # HTTPS only (production)
session.use_strict_mode = 1      # Strict session ID validation
```

## 🚨 Common Issues

### "Unable to connect to the database"
- Check database connection details in `admin/includes/db.php`
- Ensure `kuet_sports` database exists
- Verify MySQL service is running

### "Invalid username or password" always shows
- Verify admin user exists in database: `SELECT * FROM admins;`
- Check password hash is valid bcrypt format (`$2y$...`)
- Ensure password was inserted correctly

### .htaccess not working
- Your server might not have `mod_rewrite` enabled
- Test in `php.ini`: `mod_rewrite` should be enabled
- If unavailable, remove `.htaccess` (less secure but functional)

### Session not persisting
- Check browser cookie settings (allow cookies)
- Verify `session.save_path` has write permissions
- Check PHP error logs

## 📚 Further Enhancements (Future)

- [ ] Add session timeout (auto-logout after inactivity)
- [ ] Implement "Remember Me" functionality
- [ ] Add two-factor authentication (2FA)
- [ ] Create admin user management panel
- [ ] Add activity logging (who logged in when)
- [ ] Implement password reset via email
- [ ] Add CSRF tokens to forms
- [ ] Rate limiting on login attempts

## 📞 Support

If you encounter issues:
1. Check PHP error logs: `php_errors.log`
2. Check MySQL error logs
3. Verify all file permissions are correct (755 for files, 755 for dirs)
4. Ensure PHP version supports `password_hash()` (PHP 5.5+)

---

**Created:** May 15, 2026  
**Security Level:** Production-ready (basic)
