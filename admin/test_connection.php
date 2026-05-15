<?php

// Test database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "kuet_sports";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    echo "❌ Database Connection FAILED: " . mysqli_connect_error();
    exit;
}

echo "✅ Database Connection SUCCESS!<br><br>";

// Check if admins table exists
$tables_query = "SHOW TABLES LIKE 'admins'";
$tables_result = mysqli_query($conn, $tables_query);

if (mysqli_num_rows($tables_result) == 0) {
    echo "❌ 'admins' table does NOT exist!<br>";
    exit;
}

echo "✅ 'admins' table EXISTS<br><br>";

// Check if admin user exists
$check_query = "SELECT id, username, password FROM admins WHERE username = 'admin'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo "❌ Admin user does NOT exist in database!<br>";
    echo "Generating correct password hash...<br><br>";
    
    // Generate correct hash
    $correct_hash = password_hash("admin123", PASSWORD_BCRYPT);
    echo "Use this SQL to insert admin:<br>";
    echo "<pre>INSERT INTO admins (username, password) VALUES ('admin', '" . $correct_hash . "');</pre>";
    exit;
}

$admin_row = mysqli_fetch_assoc($check_result);
echo "✅ Admin user EXISTS<br>";
echo "Username: " . $admin_row['username'] . "<br>";
echo "Password Hash: " . $admin_row['password'] . "<br><br>";

// Test password verification
$test_password = "admin123";
if (password_verify($test_password, $admin_row['password'])) {
    echo "✅ Password 'admin123' MATCHES the hash<br>";
    echo "Login should work!";
} else {
    echo "❌ Password 'admin123' DOES NOT match the hash<br>";
    echo "Password hash needs to be updated.<br><br>";
    
    $correct_hash = password_hash("admin123", PASSWORD_BCRYPT);
    echo "Use this SQL to fix:<br>";
    echo "<pre>UPDATE admins SET password = '" . $correct_hash . "' WHERE username = 'admin';</pre>";
}

mysqli_close($conn);

?>
