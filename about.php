<?php
// About page - Static content with navigation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About KUET Sports - Learn more about us">
    <title>About Us - Organization of KUET Sports</title>
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

    <!-- Reusable Auth Component -->
    <?php include 'includes/auth-modal.php'; ?>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            
            <!-- Main About Content -->
            <div class="about-main">
                <div class="about-text">
                    
                    <h3>Welcome to KUET Sports</h3>
                    <p>
                        The Organization of KUET Sports is dedicated to promoting athletic excellence, 
                        physical fitness, and sportsmanship among all students at Khulna University of Engineering 
                        and Technology. Founded with the vision of making KUET a hub of sporting excellence, 
                        we have been instrumental in organizing numerous inter-university competitions, 
                        tournaments, and training programs since our establishment.
                    </p>
                    <p>
                        Based at Khulna University of Engineering & Technology, Khulna-9203, Bangladesh, 
                        our organization serves over 3,000 students across multiple departments. We believe in 
                        the power of sports to build character, foster teamwork, and create lifelong memories.
                    </p>
                </div>
                <div class="about-image">
                    <img src="image/about/img1.png" alt="KUET Sports Logo" width="350" height="350" class="about-logo">
                    <p class="image-caption">Team OKS</p>
                </div>
            </div>

            <!-- Mission Vision Goals -->
            <div class="mission-vision">
                <div class="mission">
                    <h4>Mission</h4>
                    <p>To promote a culture of health, fitness, and competitive sports while maintaining 
                    the highest standards of sportsmanship and integrity among KUET students.</p>
                </div>
                <div class="vision">
                    <h4>Vision</h4>
                    <p>To be recognized as a leading sports organization that produces champions and 
                    inspires excellence in every student athlete across Bangladesh.</p>
                </div>
                <div class="goals">
                    <h4>Goals</h4>
                    <p>To organize regular tournaments, training sessions, and sports events that 
                    encourage participation and skill development among all KUET students.</p>
                </div>
            </div>

            <!-- Sports Categories -->
            <h3 class="subsection-title">Our Sports Departments</h3>
            <div class="sports-cards">
                <a href="members.php?team=Football+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">01</div>
                        <h4>Football</h4>
                        <p>Men's and women's football teams competing in university and inter-university tournaments.</p>
                    </div>
                </a>
                <a href="members.php?team=Cricket+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">02</div>
                        <h4>Cricket</h4>
                        <p>Premier cricket tournaments including T20, ODI formats. Regular practice sessions.</p>
                    </div>
                </a>
                <a href="members.php?team=Badminton+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">03</div>
                        <h4>Badminton</h4>
                        <p>Singles and doubles competition. Inter-departmental badminton championships yearly.</p>
                    </div>
                </a>
                <a href="members.php?team=Tennis+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">04</div>
                        <h4>Tennis</h4>
                        <p>Professional coaching and training. University ranking tournaments.</p>
                    </div>
                </a>
                <a href="members.php?team=Volleyball+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">05</div>
                        <h4>Volleyball</h4>
                        <p>Men and women's volleyball leagues with quarterly tournaments.</p>
                    </div>
                </a>
                <a href="members.php?team=Athletics+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">06</div>
                        <h4>Athletics</h4>
                        <p>Track and field events including sprints, long-distance, and field events.</p>
                    </div>
                </a>
                <a href="members.php?team=Chess+Team" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">07</div>
                        <h4>Chess</h4>
                        <p>Strategic thinking competitions and chess tournaments throughout the year.</p>
                    </div>
                </a>
                <a href="members.php?team=Combat+Sports" class="sport-card-link">
                    <div class="sport-card">
                        <div class="sport-icon">08</div>
                        <h4>Combat Sports</h4>
                        <p>Boxing, martial arts, and wrestling training programs available.</p>
                    </div>
                </a>
            </div>

            <!-- Facilities Table -->
            <h3 class="subsection-title">Sports Facilities</h3>
            <table class="facilities-table">
                <thead>
                    <tr>
                        <th>Facility</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Football Ground</td>
                        <td>Main Campus</td>
                        <td>5,000+</td>
                        <td>Year-round</td>
                    </tr>
                    <tr>
                        <td>Cricket Ground</td>
                        <td>South Campus</td>
                        <td>3,000+</td>
                        <td>Sep - May</td>
                    </tr>
                    <tr>
                        <td>Badminton Court</td>
                        <td>Sports Complex</td>
                        <td>200</td>
                        <td>Year-round</td>
                    </tr>
                    <tr>
                        <td>Tennis Court</td>
                        <td>Sports Complex</td>
                        <td>150</td>
                        <td>Year-round</td>
                    </tr>
                    <tr>
                        <td>Volleyball Court</td>
                        <td>Athletic Arena</td>
                        <td>500</td>
                        <td>Year-round</td>
                    </tr>
                    <tr>
                        <td>Athletic Track</td>
                        <td>Main Stadium</td>
                        <td>2,000</td>
                        <td>Year-round</td>
                    </tr>
                    <tr>
                        <td>Gym & Fitness Center</td>
                        <td>Sports Complex</td>
                        <td>100</td>
                        <td>Year-round</td>
                    </tr>
                </tbody>
            </table>

            <!-- Achievements Timeline -->
            <h3 class="subsection-title">Major Achievements</h3>
            <div class="achievements-grid">
                <a href="gallery.php?category=event" class="achievement-card-link">
                    <div class="achievement-card">
                        <div class="achievement-year">2024</div>
                        <h4>National Champions</h4>
                        <p>Won inter-university football championship. 2nd place in cricket nationals.</p>
                    </div>
                </a>
                <a href="gallery.php?category=team" class="achievement-card-link">
                    <div class="achievement-card">
                        <div class="achievement-year">2023</div>
                        <h4>Facility Expansion</h4>
                        <p>New athletic track and upgraded gym facilities inaugurated.</p>
                    </div>
                </a>
                <a href="gallery.php?category=event" class="achievement-card-link">
                    <div class="achievement-card">
                        <div class="achievement-year">2022</div>
                        <h4>Record Participation</h4>
                        <p>3,500+ students participated in annual sports fest. Biggest event yet.</p>
                    </div>
                </a>
                <a href="gallery.php?category=team" class="achievement-card-link">
                    <div class="achievement-card">
                        <div class="achievement-year">2021</div>
                        <h4>Organization Founded</h4>
                        <p>Official establishment of Organization of KUET Sports with 8 founding members.</p>
                    </div>
                </a>
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
