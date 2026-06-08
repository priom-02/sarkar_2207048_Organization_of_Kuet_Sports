<?php
// ====================================
// Backend: Fetch Featured Content
// ====================================

session_start();
require_once 'admin/includes/db.php';

// Auto-update event status based on date/time
$now = date('Y-m-d H:i:s');

// Mark events as completed if their date has passed
$query_update = "UPDATE events SET status='completed' WHERE (DATE(date) < CURDATE()) OR (DATE(date) = CURDATE() AND TIME(date) < CURTIME())";
mysqli_query($conn, $query_update);

// Fetch upcoming and ongoing events for news ticker
$query_events = "SELECT id, title, date, status FROM events WHERE status IN ('upcoming', 'ongoing') ORDER BY date ASC LIMIT 5";
$result_events = mysqli_query($conn, $query_events);
$news_events = [];
while ($row = mysqli_fetch_assoc($result_events)) {
    $news_events[] = $row;
}

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

// Build dynamic news ticker string
$news_ticker_html = '';
if (count($news_events) > 0) {
    foreach ($news_events as $event) {
        $status_emoji = ($event['status'] == 'ongoing') ? '🔴' : '📅';
        $news_ticker_html .= $status_emoji . ' ' . htmlspecialchars($event['title']) . ' - ' . date('M d, Y', strtotime($event['date'])) . ' | ';
    }
    // Remove trailing " | "
    $news_ticker_html = rtrim($news_ticker_html, ' | ');
} else {
    $news_ticker_html = '✨ Check back soon for upcoming events! ✨';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Organization of KUET Sports - Join our sports community">
    <title>Home - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .news-ticker {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            overflow: hidden;
        }

        .news-label {
            color: white;
            font-weight: bold;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .news-content {
            flex: 1;
            overflow: hidden;
        }

        .news-content a {
            color: #ffeb3b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            white-space: nowrap;
            display: inline-block;
            padding: 0 10px;
        }

        .news-content a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .news-ticker-item {
            display: inline-block;
            margin-right: 20px;
        }

        .news-ticker-item::after {
            content: ' | ';
            margin-left: 20px;
            color: white;
        }

        .news-ticker-item:last-child::after {
            content: '';
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .scrolling-text {
            animation: scroll-left 30s linear infinite;
        }

        .stat-card-link {
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .stat-card-link:hover .stat-card {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 150, 255, 0.4);
        }

        .stat-card-link:active .stat-card {
            transform: translateY(-5px);
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
            <button class="login-btn" id="loginBtn">
                <?php 
                    if (isset($_SESSION['user'])) {
                        echo htmlspecialchars($_SESSION['user_name'] ?? 'User') . ' ▼';
                    } else {
                        echo 'Login';
                    }
                ?>
            </button>
        </div>
    </nav>

    <!-- Common Auth Modal Component -->
    <?php include 'includes/auth-modal.php'; ?>

    <!-- Home Section -->
    <section id="home" class="home">
        <div class="hero-content">
            <h1 class="hero-title">Organization of KUET Sports</h1>
            <p class="hero-tagline">Unite • Compete • Excel</p>
            
            <!-- Recent News Line -->
            <div class="news-ticker">
                <div class="news-label">📰 Latest News:</div>
                <div class="news-content">
                    <?php if (count($news_events) > 0): ?>
                        <div class="scrolling-text">
                            <?php foreach ($news_events as $index => $event): ?>
                                <span class="news-ticker-item">
                                    <span><?php echo ($event['status'] == 'ongoing') ? '🔴 ONGOING' : '📅 UPCOMING'; ?>:</span>
                                    <a href="events.php" title="Click to view event details"><?php echo htmlspecialchars($event['title']); ?> - <?php echo date('M d, Y', strtotime($event['date'])); ?></a>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="color: #ffeb3b;">✨ Check back soon for upcoming events! ✨</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="hero-stats">
                <a href="members.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($team_stats); ?></div>
                        <div class="stat-label">Sports Teams</div>
                    </div>
                </a>
                <a href="members.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total_members']; ?></div>
                        <div class="stat-label">Members</div>
                    </div>
                </a>
                <a href="events.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total_events']; ?></div>
                        <div class="stat-label">Events</div>
                    </div>
                </a>
                <a href="gallery.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total_photos']; ?></div>
                        <div class="stat-label">Photos</div>
                    </div>
                </a>
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
