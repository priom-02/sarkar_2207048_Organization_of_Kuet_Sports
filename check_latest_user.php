<?php
require_once 'admin/includes/db.php';

// Get the user we just created
$result = mysqli_query($conn, "SELECT id, full_name, email, team, created_at FROM users WHERE email = 'testuser1780953954215@example.com'");
$user = mysqli_fetch_assoc($result);

if ($user) {
    echo '<h3>✅ User Found:</h3>';
    echo '<pre>' . json_encode($user, JSON_PRETTY_PRINT) . '</pre>';
    echo '<p><strong>Team Saved:</strong> ' . ($user['team'] ? 'YES - ' . $user['team'] : 'NO (empty)') . '</p>';
} else {
    echo '<p>User not found. Let me check all users:</p>';
    $all = mysqli_query($conn, 'SELECT id, full_name, email, team, created_at FROM users ORDER BY id DESC LIMIT 5');
    echo '<pre>' . json_encode(mysqli_fetch_all($all, MYSQLI_ASSOC), JSON_PRETTY_PRINT) . '</pre>';
}
?>