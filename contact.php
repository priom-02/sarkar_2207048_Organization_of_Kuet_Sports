<?php
// ====================================
// Backend: Handle Contact Form
// ====================================

require_once 'admin/includes/db.php';

$message = "";
$error = "";
$form_submitted = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    $photo_filename = null;
    
    // Validation
    if (empty($full_name) || empty($email) || empty($message_text)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    } else {
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = mime_content_type($_FILES['photo']['tmp_name']);
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($file_type, $allowed_types)) {
                    $error = "Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.";
                } elseif ($_FILES['photo']['size'] > $max_size) {
                    $error = "File size exceeds 5MB limit.";
                } else {
                    // Create upload directory if it doesn't exist
                    $upload_dir = 'image/contact_uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    // Generate unique filename
                    $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $photo_filename = 'contact_' . time() . '_' . uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $photo_filename;
                    
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        $error = "Failed to upload photo. Please try again.";
                        $photo_filename = null;
                    }
                }
            } else {
                $error = "An error occurred during file upload. Please try again.";
            }
        }
        
        // Only proceed if no error occurred
        if (empty($error)) {
            // Save to database
            $query = "INSERT INTO contact_messages (full_name, email, message, photo) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $message_text, $photo_filename);
                
                if (mysqli_stmt_execute($stmt)) {
                    $message = "Thank you! Your submission has been received successfully. We'll get back to you soon!";
                    $form_submitted = true;
                } else {
                    $error = "An error occurred. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "Database error. Please try again later.";
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
    <meta name="description" content="Contact us - Organization of KUET Sports">
    <title>Contact - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .contact-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .contact-message.success {
            background: #dff0d8;
            border-left: 4px solid #5cb85c;
            color: #3c763d;
        }

        .contact-message.error {
            background: #f8d7da;
            border-left: 4px solid #f44336;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="image/home/logo.png" alt="KUET Sports Logo" class="navbar-logo">
                <a href="home.php" style="color: var(--secondary-blue); text-decoration: none;">KUET Sports</a>
            </div>
            <ul class="nav-links">
                <li><a href="home.php" class="nav-link">Home</a></li>
                <li><a href="about.php" class="nav-link">About</a></li>
                <li><a href="members.php" class="nav-link">Members</a></li>
                <li><a href="events.php" class="nav-link">Events</a></li>
                <li><a href="gallery.php" class="nav-link">Gallery</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <li><a href="admin/login.php" class="nav-link" style="color: #ff6b6b;">Admin</a></li>
            </ul>
            <button class="login-btn" id="loginBtn">Login</button>
        </div>
    </nav>

    <!-- Reusable Auth Component -->
    <?php include 'includes/auth-modal.php'; ?>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2 class="section-title">Join Us</h2>
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h3>Get In Touch With Us</h3>
                    <div class="contact-item">
                        <div class="contact-icon-box"></div>
                        <div>
                            <p class="contact-label">Email Address</p>
                            <p class="contact-value"><a href="mailto:sports@kuet.ac.bd">sports@kuet.ac.bd</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon-box"></div>
                        <div>
                            <p class="contact-label">Phone Number</p>
                            <p class="contact-value"><a href="tel:+8804182">+880 41 82 XXXXX</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon-box"></div>
                        <div>
                            <p class="contact-label">Office Location</p>
                            <p class="contact-value">Organization of KUET Sports<br>Khulna University of Engineering & Technology<br>Khulna-9203, Bangladesh</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon-box"></div>
                        <div>
                            <p class="contact-label">Office Hours</p>
                            <p class="contact-value">Monday - Friday: 9:00 AM - 5:00 PM<br>Saturday - Sunday: Closed</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-icon" title="Facebook"><span>f</span></a>
                        <a href="#" class="social-icon" title="Twitter"><span>𝕏</span></a>
                        <a href="#" class="social-icon" title="Instagram"><span>📸</span></a>
                        <a href="#" class="social-icon" title="LinkedIn"><span>in</span></a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    
                    <?php if (!empty($message)): ?>
                        <div class="contact-message success"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="contact-message error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" id="contactForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required value="<?php echo $form_submitted ? '' : (isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email address" required value="<?php echo $form_submitted ? '' : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" placeholder="Enter your message" rows="6" required><?php echo $form_submitted ? '' : (isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="photo" class="form-label">Photo (Optional)</label>
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" placeholder="Choose a photo">
                            <small style="color: #666; display: block; margin-top: 5px;">Supported formats: JPEG, PNG, GIF, WebP (Max 5MB)</small>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Organization of KUET Sports. All rights reserved.</p>
            <p>Khulna University of Engineering & Technology</p>
        </div>
    </footer>

    <script src="main.js"></script>
</body>
</html>
