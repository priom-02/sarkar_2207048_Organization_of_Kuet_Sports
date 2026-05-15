<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
$event = get_event($conn, $id);

if (!$event) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $status = trim($_POST['status'] ?? 'upcoming');
    
    if (empty($title) || empty($date)) {
        $error = "Title and Date are required!";
    } else {
        if (update_event($conn, $id, $title, $description, $date, $time, $location, $image, $status)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Failed to update event. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin</title>
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
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            flex: 1;
        }
        
        .btn-submit {
            background: #667eea;
            color: white;
        }
        
        .btn-submit:hover {
            background: #764ba2;
        }
        
        .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-cancel:hover {
            background: #ccc;
        }
        
        .error {
            background: #f8d7da;
            border-left: 4px solid #f44336;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
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
        <a href="index.php" class="back-link">← Back to Events</a>
        
        <div class="form-card">
            <h2>Edit Event</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo sanitize($event['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo sanitize($event['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" value="<?php echo $event['time']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value="<?php echo sanitize($event['location'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="upcoming" <?php echo $event['status'] == 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="ongoing" <?php echo $event['status'] == 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                        <option value="completed" <?php echo $event['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Image Path</label>
                    <input type="text" id="image" name="image" value="<?php echo sanitize($event['image'] ?? ''); ?>">
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-submit">Update Event</button>
                    <a href="index.php" class="btn btn-cancel" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
