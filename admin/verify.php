<?php

/**
 * ADMIN PANEL VERIFICATION CHECKLIST
 * Run this to verify everything is set up correctly
 */

echo "<h1>🔍 KUET Sports Admin Panel - Verification Checklist</h1>";
echo "<hr>";

// 1. Database Connection
echo "<h2>1️⃣ Database Connection</h2>";
$conn = mysqli_connect("localhost", "root", "", "kuet_sports");
if ($conn) {
    echo "✅ Connected to kuet_sports database<br>";
} else {
    echo "❌ Failed to connect to database<br>";
    die();
}

// 2. Admins Table
echo "<h2>2️⃣ Admins Table</h2>";
$tables_result = mysqli_query($conn, "SHOW TABLES LIKE 'admins'");
if (mysqli_num_rows($tables_result) > 0) {
    echo "✅ 'admins' table exists<br>";
    
    // Check structure
    $structure = mysqli_query($conn, "DESCRIBE admins");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($col = mysqli_fetch_assoc($structure)) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ 'admins' table NOT found<br>";
}

// 3. Admin Users
echo "<h2>3️⃣ Admin Users</h2>";
$admins = mysqli_query($conn, "SELECT id, username, email FROM admins");
if (mysqli_num_rows($admins) > 0) {
    echo "✅ Admin users exist:<br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th></tr>";
    while ($admin = mysqli_fetch_assoc($admins)) {
        echo "<tr><td>{$admin['id']}</td><td>{$admin['username']}</td><td>{$admin['email']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ No admin users found<br>";
}

// 4. File Structure
echo "<h2>4️⃣ Admin Files</h2>";
$required_files = [
    'login.php' => 'Admin login page',
    'dashboard.php' => 'Admin dashboard',
    'logout.php' => 'Logout handler',
    'index.php' => 'Index (redirects to login)',
    'includes/db.php' => 'Database connection',
    '.htaccess' => 'Security rules',
    'test_connection.php' => 'Connection tester',
    'generate_hash.php' => 'Hash generator',
];

$admin_dir = __DIR__;
foreach ($required_files as $file => $description) {
    $path = $admin_dir . '/' . $file;
    if (file_exists($path)) {
        echo "✅ $file - $description<br>";
    } else {
        echo "❌ $file - MISSING<br>";
    }
}

// 5. Password Test
echo "<h2>5️⃣ Password Verification</h2>";
$admin_result = mysqli_query($conn, "SELECT password FROM admins WHERE username = 'admin'");
if ($row = mysqli_fetch_assoc($admin_result)) {
    // Test with the password the user used
    echo "Current password hash: " . substr($row['password'], 0, 20) . "...<br>";
    
    // If they used admin54321
    if (password_verify("admin54321", $row['password'])) {
        echo "✅ Password 'admin54321' works with current hash<br>";
    }
    // If they used admin123
    elseif (password_verify("admin123", $row['password'])) {
        echo "✅ Password 'admin123' works with current hash<br>";
    } else {
        echo "❌ Password verification failed<br>";
        echo "Use generate_hash.php to create a new hash<br>";
    }
}

// 6. Session Test
echo "<h2>6️⃣ Session Test</h2>";
@session_start();
if (isset($_SESSION['admin'])) {
    echo "✅ Currently logged in as: {$_SESSION['admin']}<br>";
    echo "<a href='logout.php'>Logout</a>";
} else {
    echo "❌ Not logged in<br>";
    echo "<a href='login.php'>Go to Login</a>";
}

// 7. Summary
echo "<h2>7️⃣ Quick Links</h2>";
echo "📝 <a href='login.php'>Login Page</a><br>";
echo "🔑 <a href='generate_hash.php'>Generate Password Hash</a><br>";
echo "🧪 <a href='test_connection.php'>Test Connection</a><br>";

mysqli_close($conn);

?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
    }
    table {
        border-collapse: collapse;
        margin: 10px 0;
    }
    h1, h2 {
        color: #333;
    }
    ✅, ❌ { font-size: 18px; }
    a { color: #667eea; text-decoration: none; margin-right: 20px; }
    a:hover { text-decoration: underline; }
</style>
