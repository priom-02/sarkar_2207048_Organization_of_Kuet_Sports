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

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $team = trim($_POST['team'] ?? 'General');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $photo = trim($_POST['photo'] ?? '');
    
    if (empty($name) || empty($position) || empty($team)) {
        $error = "Name, Position, and Team are required!";
    } else {
        if (update_member($conn, $id, $name, $position, $team, $email, $phone, $bio, $photo)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Failed to update member. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Admin</title>
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
        <a href="index.php" class="back-link">← Back to Members</a>
        
        <div class="form-card">
            <h2>Edit Member</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo sanitize($member['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="position">Position *</label>
                    <input type="text" id="position" name="position" value="<?php echo sanitize($member['position']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="team">Team *</label>
                    <select id="team" name="team" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; font-family: inherit;">
                        <option value="">-- Select Team --</option>
                        <option value="Cricket Team" <?php echo ($member['team'] == 'Cricket Team') ? 'selected' : ''; ?>>Cricket Team</option>
                        <option value="Football Team" <?php echo ($member['team'] == 'Football Team') ? 'selected' : ''; ?>>Football Team</option>
                        <option value="Badminton Team" <?php echo ($member['team'] == 'Badminton Team') ? 'selected' : ''; ?>>Badminton Team</option>
                        <option value="Basketball Team" <?php echo ($member['team'] == 'Basketball Team') ? 'selected' : ''; ?>>Basketball Team</option>
                        <option value="Tennis Team" <?php echo ($member['team'] == 'Tennis Team') ? 'selected' : ''; ?>>Tennis Team</option>
                        <option value="Volleyball Team" <?php echo ($member['team'] == 'Volleyball Team') ? 'selected' : ''; ?>>Volleyball Team</option>
                        <option value="General" <?php echo ($member['team'] == 'General') ? 'selected' : ''; ?>>General</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo sanitize($member['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo sanitize($member['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio"><?php echo sanitize($member['bio'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="photo">Photo Path</label>
                    <input type="text" id="photo" name="photo" value="<?php echo sanitize($member['photo'] ?? ''); ?>">
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-submit">Update Member</button>
                    <a href="index.php" class="btn btn-cancel" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
