<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
$member = get_member($conn, $id);

if (!$member) {
    header("Location: index.php");
    exit();
}

if ($_POST['confirm'] == 'yes') {
    if (delete_member($conn, $id)) {
        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Member - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
        
        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
        }
        
        .confirmation {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .warning-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .member-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            color: #666;
        }
        
        .member-info strong {
            color: #333;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            flex: 1;
        }
        
        .btn-yes {
            background: #f44336;
            color: white;
        }
        
        .btn-yes:hover {
            background: #d32f2f;
        }
        
        .btn-no {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-no:hover {
            background: #ccc;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>KUET Sports Admin</h1>
        <div>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="confirmation">
            <div class="warning-icon">⚠️</div>
            <h2>Confirm Deletion</h2>
            
            <div class="member-info">
                <p>Are you sure you want to delete this member?</p>
                <p><strong><?php echo sanitize($member['name']); ?></strong> (<?php echo sanitize($member['position']); ?>)</p>
                <p style="font-size: 12px; color: #999; margin-top: 10px;">This action cannot be undone.</p>
            </div>
            
            <form method="POST" style="display: flex; gap: 10px;">
                <button type="submit" name="confirm" value="yes" class="btn btn-yes">Yes, Delete</button>
                <a href="index.php" class="btn btn-no" style="text-decoration: none; text-align: center;">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
