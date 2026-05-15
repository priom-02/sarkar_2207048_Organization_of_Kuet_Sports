<?php

/**
 * Password Hash Generator for Admin Panel
 * 
 * Usage:
 * 1. Change $password below to your desired password
 * 2. Visit this file in your browser
 * 3. Copy the generated hash
 * 4. Use it in SQL: UPDATE admins SET password = '[hash]' WHERE username = '[username]';
 * 5. DELETE this file after use!
 */

$password = "admin123";  // <-- CHANGE THIS TO YOUR DESIRED PASSWORD

$hash = password_hash($password, PASSWORD_BCRYPT);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Hash Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
        }
        .warning {
            background: #ffe6e6;
            border-left: 4px solid #ff4444;
            padding: 15px;
            margin-bottom: 20px;
        }
        .result {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            word-break: break-all;
        }
        code {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Hash Generator</h1>
        
        <div class="warning">
            <strong>⚠️ WARNING:</strong> Delete this file after generating hashes!
            Leaving it on the server is a security risk.
        </div>
        
        <h3>Password: <code><?php echo htmlspecialchars($password); ?></code></h3>
        
        <h3>Generated Bcrypt Hash:</h3>
        <div class="result">
            <code><?php echo htmlspecialchars($hash); ?></code>
        </div>
        
        <h3>How to use:</h3>
        <ol>
            <li>Copy the hash above</li>
            <li>Go to phpMyAdmin</li>
            <li>Run this SQL:<br>
                <code>UPDATE admins SET password = '[paste_hash_here]' WHERE username = 'admin';</code>
            </li>
            <li><strong>DELETE this file immediately!</strong></li>
        </ol>
        
        <h3>Testing:</h3>
        <p>After updating the password, you can verify it works with the test script:</p>
        <code>http://localhost/Organization%20of%20Kuet%20Sports/admin/test_connection.php</code>
    </div>
</body>
</html>
