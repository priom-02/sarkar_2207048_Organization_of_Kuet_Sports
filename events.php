<?php
// ===================================
// Backend: Fetch Events from Database
// ===================================

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "kuet_sports");

if (!$conn) {
    die("Database connection failed!");
}

// Fetch all events
$query = "SELECT * FROM events ORDER BY date ASC";
$result = mysqli_query($conn, $query);
$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = $row;
}

mysqli_close($conn);

// Helper function to format date
function formatEventDate($dateString) {
    $date = new DateTime($dateString);
    return [
        'day' => $date->format('d'),
        'month' => strtoupper($date->format('M')),
    ];
}

// Generate HTML for events
$eventsHTML = '';

if (count($events) > 0) {
    foreach ($events as $event) {
        $dateInfo = formatEventDate($event['date']);
        $eventsHTML .= '
                <!-- Event Card -->
                <div class="event-card">
                    <div class="event-date">
                        <span class="event-day">' . $dateInfo['day'] . '</span>
                        <span class="event-month">' . $dateInfo['month'] . '</span>
                    </div>';
        
        if ($event['image']) {
            $eventsHTML .= '
                    <div class="event-image">
                        <img src="' . htmlspecialchars($event['image']) . '" alt="' . htmlspecialchars($event['title']) . '" class="event-photo">
                    </div>';
        }
        
        $eventsHTML .= '
                    <div class="event-content">
                        <h3 class="event-title">' . htmlspecialchars($event['title']) . '</h3>
                        <p class="event-description">' . htmlspecialchars($event['description']) . '</p>
                        <div class="event-meta">';
        
        if ($event['location']) {
            $eventsHTML .= '<span class="event-location">' . htmlspecialchars($event['location']) . '</span>';
        }
        if ($event['time']) {
            $eventsHTML .= '<span class="event-time">' . htmlspecialchars($event['time']) . '</span>';
        }
        
        $eventsHTML .= '
                        </div>
                        <a href="#register" class="event-link">Register Now →</a>
                    </div>
                </div>
        ';
    }
} else {
    $eventsHTML = '<p style="text-align: center; color: #999;">No events scheduled yet. Check back soon!</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Events - Organization of KUET Sports">
    <title>Events - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .event-card {
            display: flex;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .event-date {
            background: linear-gradient(135deg, #0066cc, #0052a3);
            color: white;
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 100px;
            font-weight: bold;
        }

        .event-day {
            font-size: 42px;
            line-height: 1;
            margin-bottom: 5px;
        }

        .event-month {
            font-size: 14px;
            opacity: 0.95;
        }

        .event-image {
            width: 150px;
            height: 100%;
            min-height: 200px;
            overflow: hidden;
        }

        .event-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .event-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 10px 0;
            line-height: 1.3;
        }

        .event-description {
            font-size: 14px;
            color: #666;
            margin: 0 0 15px 0;
            line-height: 1.5;
        }

        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
            flex: 1;
        }

        .event-location,
        .event-time {
            font-size: 13px;
            color: #888;
            display: flex;
            align-items: center;
        }

        .event-location::before {
            content: "Location: ";
            font-weight: 600;
            margin-right: 5px;
            color: #555;
        }

        .event-time::before {
            content: "Time: ";
            font-weight: 600;
            margin-right: 5px;
            color: #555;
        }

        .event-link {
            align-self: flex-start;
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-top: auto;
        }

        .event-link:hover {
            background: rgba(0, 102, 204, 0.1);
            color: #0052a3;
        }
    </style>
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

    <!-- Events Section -->
    <section id="events" class="events">
        <div class="container">
            <h2 class="section-title">Upcoming Events</h2>
            <p class="events-subtitle">Be part of our exciting sports events and tournaments</p>
            
            <div class="events-grid">
                <?php echo $eventsHTML; ?>
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
