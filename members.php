<?php
// ====================================
// Backend: Fetch Members from Database
// ====================================

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "kuet_sports");

if (!$conn) {
    die("Database connection failed!");
}

// Fetch all members
$query = "SELECT * FROM members ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);
$members = [];

while ($row = mysqli_fetch_assoc($result)) {
    $members[] = $row;
}

mysqli_close($conn);

// Generate HTML for members
$membersHTML = '';

if (count($members) > 0) {
    foreach ($members as $member) {
        $membersHTML .= '
                <!-- Member Card -->
                <div class="member-card">
                    <div class="member-image">
                        <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['name']) . '">
                    </div>
                    <h3 class="member-name">' . htmlspecialchars($member['name']) . '</h3>
                    <p class="member-position">' . htmlspecialchars($member['position']) . '</p>
                    <p class="member-department">' . htmlspecialchars($member['bio']) . '</p>
                </div>
        ';
    }
} else {
    $membersHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">No members found.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Members - Organization of KUET Sports">
    <title>Members - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="image/home/logo.png" alt="KUET Sports Logo" class="navbar-logo">
                <a href="home.html" style="color: var(--secondary-blue); text-decoration: none;">KUET Sports</a>
            </div>
            <ul class="nav-links">
                <li><a href="home.html" class="nav-link">Home</a></li>
                <li><a href="about.html" class="nav-link">About</a></li>
                <li><a href="members.php" class="nav-link">Members</a></li>
                <li><a href="events.php" class="nav-link">Events</a></li>
                <li><a href="gallery.php" class="nav-link">Gallery</a></li>
                <li><a href="contact.html" class="nav-link">Contact</a></li>
                <li><a href="admin/login.php" class="nav-link" style="color: #ff6b6b;">Admin</a></li>
            </ul>
            <button class="login-btn" id="loginBtn">Login</button>
        </div>
    </nav>

    <!-- Login Modal -->
    <div class="login-modal" id="loginModal">
        <div class="login-container">
            <span class="close-btn" id="closeBtn">&times;</span>
            
            <!-- Tab Buttons -->
            <div class="login-tabs">
                <button class="tab-btn active" data-tab="signin">Sign In</button>
                <button class="tab-btn" data-tab="signup">Sign Up</button>
            </div>

            <!-- Sign In Form -->
            <form class="login-form active" id="signinForm">
                <h2>Sign In</h2>
                <div class="form-group">
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" required>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <button type="submit" class="submit-btn">Sign In</button>
                <p class="form-footer">Don't have an account? <a href="#" class="tab-link" data-tab="signup">Sign Up</a></p>
            </form>

            <!-- Sign Up Form -->
            <form class="login-form" id="signupForm">
                <h2>Sign Up</h2>
                <div class="form-group">
                    <input type="text" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Confirm Password" required>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" id="terms">
                    <label for="terms">I agree to the terms and conditions</label>
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
                <p class="form-footer">Already have an account? <a href="#" class="tab-link" data-tab="signin">Sign In</a></p>
            </form>
        </div>
    </div>

     <!-- Members Section -->
    <section id="members" class="members">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="members-grid" id="membersGrid">
                <?php echo $membersHTML; ?>
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
