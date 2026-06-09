<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

$id = intval($_GET['id'] ?? 0);

// Fetch message
$query = "SELECT * FROM contact_messages WHERE id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$message = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$message) {
    header("Location: index.php");
    exit();
}

// Mark as read if new
if ($message['status'] == 'new') {
    $query = "UPDATE contact_messages SET status='read' WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message - Admin</title>
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
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .message-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .message-header h2 {
            color: #333;
            margin: 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .status-new {
            background: #ffeaa7;
            color: #d63031;
        }
        
        .status-read {
            background: #dfe6e9;
            color: #2d3436;
        }
        
        .status-replied {
            background: #55efc4;
            color: #00b894;
        }
        
        .message-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
            font-size: 13px;
        }
        
        .info-value {
            color: #333;
            font-size: 15px;
        }
        
        .message-body {
            margin-bottom: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            border-radius: 5px;
            min-height: 150px;
            line-height: 1.6;
            color: #333;
            white-space: pre-wrap;
            word-wrap: break-word;
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
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-back {
            background: #667eea;
            color: white;
            flex: 1;
            text-align: center;
        }
        
        .btn-back:hover {
            background: #5568d3;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
            flex: 1;
            text-align: center;
        }
        
        .btn-delete:hover {
            background: #da190b;
        }
        
        .back-link {
            color: #667eea;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .photo-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
            text-align: center;
        }
        
        .photo-label {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 15px;
            font-size: 14px;
            display: block;
        }
        
        .photo-container {
            max-width: 300px;
            margin: 0 auto;
        }
        
        .photo-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .no-photo {
            padding: 40px 20px;
            color: #999;
            font-style: italic;
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
        <a href="index.php" class="back-link">← Back to Messages</a>
        
        <div class="message-card">
            <div class="message-header">
                <h2>Message from <?php echo htmlspecialchars($message['full_name']); ?></h2>
                <span class="status-badge status-<?php echo $message['status']; ?>">
                    <?php echo ucfirst($message['status']); ?>
                </span>
            </div>
            
            <div class="message-info">
                <div class="info-item">
                    <span class="info-label">From:</span>
                    <span class="info-value"><?php echo htmlspecialchars($message['full_name']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date Received:</span>
                    <span class="info-value"><?php echo date('M d, Y at H:i', strtotime($message['created_at'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><?php echo ucfirst($message['status']); ?></span>
                </div>
            </div>
            
            <?php if (isset($message['photo']) && !empty($message['photo'])): ?>
            <div class="photo-section">
                <span class="photo-label">Attached Photo:</span>
                <div class="photo-container">
                    <img src="../../image/contact_uploads/<?php echo htmlspecialchars($message['photo']); ?>" alt="User submitted photo">
                </div>
            </div>
            <?php endif; ?>
            
            <div class="message-body">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
            
            <div class="button-group">
                <a href="index.php" class="btn btn-back">Back to Messages</a>
                <a href="index.php?delete=<?php echo $message['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this message?');">Delete Message</a>
            </div>
        </div>
    </div>
</body>
</html>
