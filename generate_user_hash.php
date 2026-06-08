<?php
/**
 * Password Hash Generator
 * 
 * Use this file to generate secure password hashes for users
 * 
 * Usage:
 * 1. Access: http://localhost/path/generate_user_hash.php
 * 2. Enter your password
 * 3. Copy the generated hash
 * 4. Use it in the database INSERT statement
 */

// Check if form was submitted
$hash = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    if (empty($password)) {
        $error = 'Please enter a password';
    } else if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .hash-output {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        
        .copy-btn {
            margin-top: 10px;
            background: #28a745;
            font-size: 14px;
            padding: 8px;
        }
        
        .copy-btn:hover {
            background: #218838;
        }
        
        .info {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 30px;
            font-size: 13px;
            color: #0c5aa0;
        }
        
        .info h3 {
            margin-top: 15px;
            margin-bottom: 8px;
            color: #0c5aa0;
        }
        
        .info p {
            margin-bottom: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Hash Generator</h1>
        <p class="subtitle">Generate secure bcrypt hashes for user accounts</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="password">Enter Password to Hash:</label>
                <input type="password" id="password" name="password" placeholder="e.g., MySecurePassword123" required>
            </div>
            
            <button type="submit">Generate Hash</button>
        </form>
        
        <?php if ($hash): ?>
            <div class="alert alert-success">✓ Hash generated successfully!</div>
            
            <div class="hash-output">
                <strong>Hashed Password:</strong><br>
                <?php echo htmlspecialchars($hash); ?>
            </div>
            
            <button class="copy-btn" onclick="copyToClipboard('<?php echo addslashes($hash); ?>')">
                📋 Copy to Clipboard
            </button>
            
            <div class="info">
                <h3>How to Use This Hash:</h3>
                <p>1. Copy the hash above</p>
                <p>2. Use in SQL INSERT:</p>
                <code style="display: block; background: white; padding: 10px; margin: 10px 0; border-radius: 3px;">
INSERT INTO users (full_name, email, password)<br>
VALUES ('John Doe', 'john@example.com', '[paste-hash-here]');
                </code>
                <p>3. The user can then login with the password you entered</p>
            </div>
        <?php endif; ?>
        
        <div class="info">
            <h3>💡 Quick Test Account:</h3>
            <p><strong>For Testing Purpose Only:</strong></p>
            <p>Email: test@example.com<br>
            Password: test123</p>
            <p>To create this test account, use the hash below in your SQL INSERT:</p>
            <code style="display: block; background: white; padding: 10px; margin: 10px 0; border-radius: 3px; font-size: 11px;">
<?php echo htmlspecialchars(password_hash('test123', PASSWORD_BCRYPT)); ?>
            </code>
        </div>
    </div>
    
    <script>
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✓ Hash copied to clipboard!');
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }
    
    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('✓ Hash copied to clipboard!');
    }
    </script>
</body>
</html>
