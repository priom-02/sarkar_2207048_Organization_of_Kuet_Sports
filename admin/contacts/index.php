<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Mark message as read if ID provided
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $id = intval($_GET['read']);
    $query = "UPDATE contact_messages SET status='read' WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Delete message if ID provided
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM contact_messages WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit();
}

// Fetch all contact messages
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin</title>
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
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        h2 {
            color: #333;
        }
        
        .messages-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
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
        
        .message-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #666;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-view {
            background: #667eea;
            color: white;
        }
        
        .btn-view:hover {
            background: #5568d3;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
        }
        
        .btn-delete:hover {
            background: #da190b;
        }
        
        .btn-mark-read {
            background: #4caf50;
            color: white;
        }
        
        .btn-mark-read:hover {
            background: #45a049;
        }
        
        .empty-message {
            padding: 40px;
            text-align: center;
            color: #999;
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
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .message-preview {
                max-width: 150px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 10px;
            }
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
        <a href="../dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <h2>Contact Messages</h2>
        
        <?php
        $total = count($messages);
        $new = 0;
        $read = 0;
        $replied = 0;
        
        foreach ($messages as $msg) {
            if ($msg['status'] == 'new') $new++;
            elseif ($msg['status'] == 'read') $read++;
            elseif ($msg['status'] == 'replied') $replied++;
        }
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total; ?></div>
                <div class="stat-label">Total Messages</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $new; ?></div>
                <div class="stat-label">New Messages</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $read; ?></div>
                <div class="stat-label">Read</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $replied; ?></div>
                <div class="stat-label">Replied</div>
            </div>
        </div>
        
        <div class="messages-table">
            <?php if (count($messages) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td class="message-preview" title="<?php echo htmlspecialchars($msg['message']); ?>">
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $msg['status']; ?>">
                                        <?php echo ucfirst($msg['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $msg['id']; ?>" class="btn btn-view">View</a>
                                        <?php if ($msg['status'] == 'new'): ?>
                                            <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-mark-read">Mark Read</a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">
                    <p>No messages received yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
