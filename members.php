<?php
// ====================================
// Backend: Fetch Members from Database
// ====================================

// Connect to database
require_once 'admin/includes/db.php';

// Get team filter from URL parameter if provided
$team_filter = isset($_GET['team']) ? htmlspecialchars($_GET['team']) : null;
$page_title = "Members";

// Fetch all members ordered by team and position
if ($team_filter) {
    $query = "SELECT * FROM members WHERE team = ? ORDER BY FIELD(position, 'Captain', 'Vice Captain', 'Goalkeeper', 'Wicket Keeper', 'All-rounder', 'Bowler', 'Forward', 'Midfielder', 'Defender', 'Player'), name ASC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $team_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $page_title = htmlspecialchars($team_filter) . " - Members";
} else {
    $query = "SELECT * FROM members ORDER BY team ASC, FIELD(position, 'Captain', 'Vice Captain', 'Goalkeeper', 'Wicket Keeper', 'All-rounder', 'Bowler', 'Forward', 'Midfielder', 'Defender', 'Player'), name ASC";
    $result = mysqli_query($conn, $query);
}

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$members = [];
$teams = [];

// Group members by team
while ($row = mysqli_fetch_assoc($result)) {
    $team = $row['team'] ?? 'General';
    if (!isset($teams[$team])) {
        $teams[$team] = [];
    }
    $teams[$team][] = $row;
    $members[] = $row;
}

// Generate HTML for team sections
$teamsHTML = '';

if (count($teams) > 0) {
    foreach ($teams as $teamName => $teamMembers) {
        $teamsHTML .= '
        <!-- Team Section -->
        <div class="team-section" id="team-' . htmlspecialchars(str_replace(' ', '-', strtolower($teamName))) . '">
            <div class="team-header">
                <h3 class="team-name">' . htmlspecialchars($teamName) . '</h3>
                <p class="team-count">' . count($teamMembers) . ' Members</p>
            </div>
            <div class="team-members-grid">';
        
        foreach ($teamMembers as $member) {
            $teamsHTML .= '
                <!-- Member Card -->
                <div class="member-card">
                    <div class="member-image-wrapper">
                        <img src="' . htmlspecialchars($member['photo']) . '" alt="' . htmlspecialchars($member['name']) . '" class="member-image">
                        <div class="member-badge">' . htmlspecialchars($member['position']) . '</div>
                    </div>
                    <div class="member-info">
                        <h4 class="member-name">' . htmlspecialchars($member['name']) . '</h4>
                        <p class="member-position">' . htmlspecialchars($member['position']) . '</p>
                        <p class="member-bio">' . htmlspecialchars($member['bio']) . '</p>
                        <div class="member-contact">
                            <span class="contact-item" title="Email">
                                <i>✉</i> ' . htmlspecialchars($member['email']) . '
                            </span>
                            <span class="contact-item" title="Phone">
                                <i>☎</i> ' . htmlspecialchars($member['phone']) . '
                            </span>
                        </div>
                    </div>
                </div>
            ';
        }
        
        $teamsHTML .= '
            </div>
        </div>
        ';
    }
} else {
    $teamsHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">No members found.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Members - Organization of KUET Sports">
    <title><?php echo $page_title; ?> - Organization of KUET Sports</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .members {
            padding: 60px 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: calc(100vh - 80px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 50px;
            color: #333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Team Section Styling */
        .team-section {
            margin-bottom: 60px;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0066cc;
        }

        .team-name {
            font-size: 28px;
            font-weight: bold;
            color: #0066cc;
            margin: 0;
        }

        .team-count {
            background: #e3f2fd;
            color: #0066cc;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin: 0;
            font-size: 14px;
        }

        /* Members Grid */
        .team-members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .member-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .member-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border-color: #0066cc;
        }

        .member-image-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: linear-gradient(135deg, #0066cc, #0052a3);
        }

        .member-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .member-card:hover .member-image {
            transform: scale(1.05);
        }

        .member-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff6b6b;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .member-info {
            padding: 20px;
        }

        .member-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0 0 8px 0;
        }

        .member-position {
            font-size: 14px;
            color: #0066cc;
            font-weight: 600;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .member-bio {
            font-size: 13px;
            color: #666;
            margin: 0 0 15px 0;
            line-height: 1.5;
            min-height: 35px;
        }

        .member-contact {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
        }

        .contact-item {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
            word-break: break-word;
        }

        .contact-item i {
            color: #0066cc;
            font-style: normal;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .team-members-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }

            .team-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .team-name {
                font-size: 22px;
            }

            .members {
                padding: 40px 15px;
            }

            .team-section {
                padding: 25px;
            }

            .section-title {
                font-size: 32px;
                margin-bottom: 35px;
            }
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
            <h2 class="section-title"><?php echo $team_filter ? htmlspecialchars($team_filter) . ' Members' : 'Our Teams'; ?></h2>
            <?php if ($team_filter): ?>
                <div style="text-align: center; margin-bottom: 20px;">
                    <a href="members.php" style="color: #0066cc; text-decoration: none; font-weight: 500;">← Back to All Teams</a>
                </div>
            <?php endif; ?>
            <div class="teams-container">
                <?php echo $teamsHTML; ?>
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
