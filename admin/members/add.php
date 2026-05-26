<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $team = trim($_POST['team'] ?? 'General');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $photo = 'image/members/default.png'; // Default photo path
    
    if (empty($name) || empty($position) || empty($team)) {
        $error = "Name, Position, and Team are required!";
    } else {
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $upload_dir = '../../image/members/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Validate file type by extension
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $file_name = $_FILES['photo']['name'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_extensions)) {
                $error = "Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed!";
            } else if ($_FILES['photo']['size'] > 5 * 1024 * 1024) { // 5MB limit
                $error = "File size exceeds 5MB limit!";
            } else {
                $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", $file_name);
                $upload_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    $photo = 'image/members/' . $filename;
                } else {
                    $error = "Failed to upload photo. Please try again.";
                }
            }
        }
        
        if (empty($error)) {
            if (add_member($conn, $name, $position, $team, $email, $phone, $bio, $photo)) {
                $message = "Member added successfully!";
                header("Location: index.php");
                exit();
            } else {
                $error = "Failed to add member. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member - Admin</title>
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
        
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        
        input:focus, textarea:focus {
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
        
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .success {
            background: #dff0d8;
            border-left: 4px solid #5cb85c;
            color: #3c763d;
        }
        
        .error {
            background: #f8d7da;
            border-left: 4px solid #f44336;
            color: #721c24;
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
        <a href="index.php" class="back-link">← Back to Members</a>
        
        <div class="form-card">
            <h2>Add New Member</h2>
            
            <?php if (!empty($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($message)): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" id="position" name="position" placeholder="e.g., Captain, Vice Captain" required>
                </div>
                
                <div class="form-group">
                    <label for="team">Team *</label>
                    <select id="team" name="team" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; font-family: inherit;">
                        <option value="">-- Select Team --</option>
                        <option value="Cricket Team">Cricket Team</option>
                        <option value="Football Team">Football Team</option>
                        <option value="Badminton Team">Badminton Team</option>
                        <option value="Basketball Team">Basketball Team</option>
                        <option value="Tennis Team">Tennis Team</option>
                        <option value="Volleyball Team">Volleyball Team</option>
                        <option value="General">General</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="member@kuet.edu.bd">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="+880-1XXX-XXXX">
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Brief biography or description"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="photo">Member Photo (JPG, PNG, GIF, WEBP - Max 5MB)</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp">
                    <small style="color: #999; display: block; margin-top: 5px;">Upload a photo of the member. If not provided, a default image will be used.</small>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-submit">Save Member</button>
                    <a href="index.php" class="btn btn-cancel" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
