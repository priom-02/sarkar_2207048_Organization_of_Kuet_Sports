<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$gallery = get_all_gallery($conn);
$count = mysqli_num_rows($gallery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Admin</title>
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
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .gallery-thumbnail {
            width: 100%;
            height: 150px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 48px;
        }
        
        .gallery-info {
            padding: 15px;
        }
        
        .gallery-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .gallery-category {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }
        
        .gallery-actions {
            display: flex;
            gap: 5px;
        }
        
        .btn-edit, .btn-delete {
            flex: 1;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            text-align: center;
        }
        
        .btn-edit {
            background: #4CAF50;
            color: white;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
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
            <h2>Manage Gallery (<?php echo $count; ?>)</h2>
            <a href="add.php" class="btn-primary">+ Add Photo</a>
        </div>
        
        <?php if ($count > 0): ?>
            <div class="gallery-grid">
                <?php while ($item = mysqli_fetch_assoc($gallery)): ?>
                    <div class="gallery-item">
                        <div class="gallery-thumbnail">
                            <img src="../../<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo sanitize($item['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="gallery-info">
                            <div class="gallery-title" title="<?php echo sanitize($item['title']); ?>"><?php echo sanitize($item['title']); ?></div>
                            <div class="gallery-category"><?php echo sanitize($item['category'] ?? 'Uncategorized'); ?></div>
                            <div class="gallery-actions">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn-edit">Edit</a>
                                <a href="delete.php?id=<?php echo $item['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>No photos in gallery. <a href="add.php" style="color: #667eea;">Upload one now</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
