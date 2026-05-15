<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$events = get_all_events($conn);
$count = mysqli_num_rows($events);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin</title>
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
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            color: #333;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary:hover {
            background: #764ba2;
        }
        
        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        
        .btn-edit {
            background: #4CAF50;
            color: white;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
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
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status.upcoming {
            background: #bbdefb;
            color: #1976d2;
        }
        
        .status.ongoing {
            background: #fff9c4;
            color: #f57f17;
        }
        
        .status.completed {
            background: #c8e6c9;
            color: #388e3c;
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
        
        <div class="header">
            <div>
                <h2>Manage Events (<?php echo $count; ?>)</h2>
                <a href="../../admin/registrations/index.php" style="color: #667eea; text-decoration: none; font-size: 14px; margin-top: 5px; display: inline-block;">View Event Registrations →</a>
            </div>
            <a href="add.php" class="btn-primary">+ Add New Event</a>
        </div>
        
        <?php if ($count > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = mysqli_fetch_assoc($events)): ?>
                        <tr>
                            <td><?php echo sanitize($event['title']); ?></td>
                            <td><?php echo format_date($event['date']); ?></td>
                            <td><?php echo sanitize($event['location'] ?? 'N/A'); ?></td>
                            <td><span class="status <?php echo $event['status']; ?>"><?php echo ucfirst($event['status']); ?></span></td>
                            <td>
                                <a href="edit.php?id=<?php echo $event['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete.php?id=<?php echo $event['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No events found. <a href="add.php" style="color: #667eea;">Add one now</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
