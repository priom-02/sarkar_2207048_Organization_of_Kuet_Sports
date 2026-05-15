<?php

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

// Get counts for dashboard
$members_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members"))['count'];
$events_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM events"))['count'];
$gallery_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM gallery"))['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KUET Sports</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .username {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .dashboard-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-content h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .welcome-message {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .module {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
            cursor: pointer;
        }
        
        .module:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .module h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .module .count {
            color: #667eea;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .module p {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>KUET Sports Admin</h1>
        <div class="admin-info">
            <span class="username">Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-content">
            <h2>Dashboard</h2>
            
            <div class="welcome-message">
                <p>Welcome to the KUET Sports Organization Admin Panel. You are logged in as <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong>.</p>
                <p>This is where you can manage all aspects of the organization.</p>
            </div>
            
            <div class="modules">
                <a href="members/index.php" class="module">
                    <h3>👥 Members</h3>
                    <p class="count"><?php echo $members_count; ?> Members</p>
                    <p>Manage organization members</p>
                </a>
                
                <a href="events/index.php" class="module">
                    <h3>📅 Events</h3>
                    <p class="count"><?php echo $events_count; ?> Events</p>
                    <p>Create and manage sports events</p>
                </a>
                
                <a href="gallery/index.php" class="module">
                    <h3>🖼️ Gallery</h3>
                    <p class="count"><?php echo $gallery_count; ?> Photos</p>
                    <p>Upload and organize photos</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
