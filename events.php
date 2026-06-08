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
                        <a href="#" class="event-link register-btn" onclick="openRegistration(' . $event['id'] . '); return false;">Register Now →</a>
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

        /* Registration Modal Styles */
        .registration-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .registration-modal.active {
            display: flex;
        }

        .registration-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .registration-container h2 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #1a1a1a;
            font-size: 24px;
        }

        .registration-container .form-group {
            margin-bottom: 15px;
        }

        .registration-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }

        .registration-container input,
        .registration-container select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            box-sizing: border-box;
        }

        .registration-container input:focus,
        .registration-container select:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }

        .registration-container .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0066cc, #0052a3);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .registration-container .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 102, 204, 0.4);
        }

        #registrationMessage {
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        #registrationMessage.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        #registrationMessage.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

    <!-- Event Registration Modal -->
    <div class="registration-modal" id="registrationModal">
        <div class="registration-container">
            <span class="close-btn" id="closeRegisterBtn">&times;</span>
            <h2>Event Registration Form</h2>
            <form id="registrationForm">
                <input type="hidden" id="eventId" name="event_id" value="">
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department">
                        <option value="">Select Department</option>
                        <option value="Computer Science & Engineering">Computer Science & Engineering</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                        <option value="Electrical Engineering">Electrical Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Chemical Engineering">Chemical Engineering</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Team Name (if applicable)</label>
                    <input type="text" name="team_name" placeholder="Enter team name">
                </div>

                <div class="form-group">
                    <label>Experience Level *</label>
                    <select name="experience_level" required>
                        <option value="">Select Level</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Professional">Professional</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Submit Registration</button>
            </form>
            <div id="registrationMessage" style="display: none; margin-top: 15px; padding: 12px; border-radius: 6px; text-align: center;"></div>
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
    <script>
        // Open Registration Modal
        function openRegistration(eventId) {
            document.getElementById('eventId').value = eventId;
            document.getElementById('registrationForm').reset();
            document.getElementById('registrationMessage').style.display = 'none';
            document.getElementById('registrationModal').classList.add('active');
        }

        // Close Registration Modal
        document.getElementById('closeRegisterBtn').addEventListener('click', function() {
            document.getElementById('registrationModal').classList.remove('active');
        });

        // Close modal when clicking outside
        document.getElementById('registrationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });

        // Handle Registration Form Submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('registrationMessage');

            fetch('register-event.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'success';
                    messageDiv.textContent = data.message;
                    setTimeout(() => {
                        document.getElementById('registrationModal').classList.remove('active');
                    }, 2000);
                } else {
                    messageDiv.className = 'error';
                    messageDiv.textContent = data.message;
                }
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.className = 'error';
                messageDiv.textContent = 'Error: ' + error.message;
            });
        });
    </script>
</body>
</html>
