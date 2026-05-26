<?php
// =======================================
// Backend: Fetch Gallery Items from Database
// =======================================

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "kuet_sports");

if (!$conn) {
    die("Database connection failed!");
}

// Get category filter from URL parameter if provided
$active_filter = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'all';

// Fetch all gallery items
$query = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $query);
$gallery = [];
$categories = ['all'];

while ($row = mysqli_fetch_assoc($result)) {
    $gallery[] = $row;
    if ($row['category'] && !in_array($row['category'], $categories)) {
        $categories[] = $row['category'];
    }
}

mysqli_close($conn);

// Generate filter buttons HTML
$filterHTML = '<button class="filter-btn' . ($active_filter === 'all' ? ' active' : '') . '" data-filter="all">All</button>';

foreach ($categories as $cat) {
    if ($cat !== 'all') {
        $active_class = ($active_filter === $cat) ? ' active' : '';
        $filterHTML .= '<button class="filter-btn' . $active_class . '" data-filter="' . htmlspecialchars($cat) . '">' . ucfirst(htmlspecialchars($cat)) . '</button>';
    }
}

// Generate gallery items HTML
$galleryHTML = '';

if (count($gallery) > 0) {
    foreach ($gallery as $item) {
        $category = $item['category'] ?? 'other';
        $should_display = ($active_filter === 'all' || $active_filter === $category) ? 'block' : 'none';
        $galleryHTML .= '
                <!-- Gallery Item -->
                <div class="gallery-item" data-category="' . htmlspecialchars($category) . '" style="display: ' . $should_display . ';">
                    <div class="gallery-image">
                        <img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '" class="gallery-photo">
                        <div class="gallery-overlay">
                            <h3>' . htmlspecialchars($item['title']) . '</h3>';
        
        if ($item['description']) {
            $galleryHTML .= '<p>' . htmlspecialchars($item['description']) . '</p>';
        }
        
        $galleryHTML .= '
                        </div>
                    </div>
                </div>
        ';
    }
} else {
    $galleryHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">No photos in gallery yet. Check back soon!</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gallery - Organization of KUET Sports">
    <title>Gallery - Organization of KUET Sports</title>
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

    <!-- Gallery Section -->
    <section id="gallery" class="gallery">
        <div class="container">
            <h2 class="section-title">Gallery</h2>
            <p class="gallery-subtitle">Explore our memorable moments and sporting events</p>
            
            <?php if ($active_filter !== 'all'): ?>
                <div style="text-align: center; margin-bottom: 20px;">
                    <a href="gallery.php" style="color: #0066cc; text-decoration: none; font-weight: 500;">← View All Photos</a>
                    <p style="color: #666; font-size: 14px; margin-top: 5px;">Currently viewing: <strong><?php echo ucfirst(htmlspecialchars($active_filter)); ?></strong></p>
                </div>
            <?php endif; ?>
            
            <!-- Gallery Filter -->
            <div class="gallery-filter" id="galleryFilter">
                <?php echo $filterHTML; ?>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="galleryGrid">
                <?php echo $galleryHTML; ?>
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
