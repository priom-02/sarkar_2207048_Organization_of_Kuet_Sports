<?php
// Test the signup API directly

// Simulate a signup request
$postData = [
    'action' => 'signup',
    'full_name' => 'Debug Test User',
    'email' => 'debugtest_' . time() . '@test.com',
    'password' => 'test123456',
    'confirm_password' => 'test123456'
];

// Convert to query string for curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/Organization%20of%20Kuet%20Sports/auth-backend.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup Debug Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .info { background: #e3f2fd; border: 1px solid #2196f3; padding: 15px; border-radius: 5px; margin-bottom: 20px; color: #0d47a1; }
        .debug-box { background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .debug-box h3 { margin-bottom: 10px; color: #333; }
        pre { background: white; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; color: #333; border: 1px solid #ddd; }
        .success { border-left: 4px solid #4caf50; background: #f1f8f4; }
        .error { border-left: 4px solid #f44336; background: #fef5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Signup API Debug Test</h1>
        
        <div class="info">
            <strong>This page tests the signup API directly</strong><br>
            It bypasses the form and frontend to test if the backend is working correctly.
        </div>

        <div class="debug-box">
            <h3>📊 API Response</h3>
            <pre><?php echo htmlspecialchars($response); ?></pre>
        </div>

        <div class="debug-box">
            <h3>📋 Request Details</h3>
            <pre>HTTP Code: <?php echo $httpCode; ?>
Action: signup
Timestamp: <?php echo date('Y-m-d H:i:s'); ?></pre>
        </div>

        <?php
        $responseData = json_decode($response, true);
        if ($responseData) {
            $success = $responseData['success'] ?? false;
            ?>
            <div class="debug-box <?php echo $success ? 'success' : 'error'; ?>">
                <h3><?php echo $success ? '✅ Success' : '❌ Failed'; ?></h3>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($responseData['message'] ?? 'No message'); ?></p>
                
                <?php if (isset($responseData['debug'])): ?>
                    <p style="margin-top: 10px;"><strong>Debug Info:</strong></p>
                    <pre><?php echo json_encode($responseData['debug'], JSON_PRETTY_PRINT); ?></pre>
                <?php endif; ?>
            </div>
            <?php
        }
        ?>

        <div class="debug-box">
            <h3>🗄️ Check Database</h3>
            <p><a href="debug-db.php" style="color: #2196f3; text-decoration: none;">View recent users in database →</a></p>
        </div>
    </div>
</body>
</html>
