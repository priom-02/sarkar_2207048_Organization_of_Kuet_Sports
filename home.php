<?php
// ====================================
// Backend: Fetch Featured Content
// ====================================

require_once 'admin/includes/db.php';

// Fetch member count by team
$query_teams = "SELECT team, COUNT(*) as count FROM members GROUP BY team ORDER BY count DESC";
$result_teams = mysqli_query($conn, $query_teams);
$team_stats = [];
while ($row = mysqli_fetch_assoc($result_teams)) {
    $team_stats[] = $row;
}

// Total counts
$query_total = "SELECT 
    (SELECT COUNT(*) FROM members) as total_members,
    (SELECT COUNT(*) FROM events) as total_events,
    (SELECT COUNT(*) FROM gallery) as total_photos";
$result_total = mysqli_query($conn, $query_total);
$stats = mysqli_fetch_assoc($result_total);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Organization of KUET Sports - Join our sports community">
    <title>Home - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
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

    <!-- Home Section -->
    <section id="home" class="home">
        <div class="hero-content">
            <h1 class="hero-title">Organization of KUET Sports</h1>
            <p class="hero-tagline">Unite • Compete • Excel</p>
            
            <!-- Recent News Line -->
            <div class="news-ticker">
                <div class="news-label">📰 Latest News:</div>
                <div class="news-content">
                    <marquee behavior="scroll" direction="left" scrollamount="3">
                        🎉 Football Championship 2024 Winners Announced! | 🏆 Cricket Team Qualifies for National Tournament | ⚡ New Gym Facilities Now Open | 🎊 Annual Sports Fest Coming Soon!
                    </marquee>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="hero-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($team_stats); ?></div>
                    <div class="stat-label">Sports Teams</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_members']; ?></div>
                    <div class="stat-label">Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_events']; ?></div>
                    <div class="stat-label">Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats['total_photos']; ?></div>
                    <div class="stat-label">Photos</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <!-- Removed - Homepage shows only hero section -->

    <!-- Featured Gallery Section -->
    <!-- Removed - Homepage shows only hero section -->

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
