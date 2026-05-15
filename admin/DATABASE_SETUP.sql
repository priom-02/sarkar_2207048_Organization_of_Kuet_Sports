-- ========================================
-- KUET SPORTS DATABASE TABLES
-- ========================================

-- ==========================================
-- 1. MEMBERS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    bio TEXT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 2. EVENTS TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME,
    location VARCHAR(150),
    image VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- 3. GALLERY TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50),
    image VARCHAR(255) NOT NULL,
    description TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==========================================
-- SAMPLE DATA (Optional - for testing)
-- ==========================================

-- Sample Members
INSERT INTO members (name, position, email, phone, bio, photo) VALUES
('Ahmed Hassan', 'Captain', 'ahmed@kuet.edu.bd', '+880-1XXX-XXXX', 'Sports enthusiast and team leader', 'image/members/ahnaf_tajwar_sadi.png'),
('Fatima Khan', 'Vice Captain', 'fatima@kuet.edu.bd', '+880-1XXX-XXXX', 'Dedicated athlete', 'image/members/ariful-islam-sheikh.png'),
('Karim Ali', 'Treasurer', 'karim@kuet.edu.bd', '+880-1XXX-XXXX', 'Finance manager', 'image/members/hasib_mahmud.png'),
('Md Abu Rayhan', 'Sports Coordinator', 'rayhan@kuet.edu.bd', '+880-1XXX-XXXX', 'Event organizer', 'image/members/md_abu_rayhan.png'),
('Shariar Abdullah', 'Secretary', 'shariar@kuet.edu.bd', '+880-1XXX-XXXX', 'Team manager', 'image/members/shariar-abdullah.png'),
('SK Mahin Ahmed', 'Joint Secretary', 'mahin@kuet.edu.bd', '+880-1XXX-XXXX', 'Assistant coordinator', 'image/members/sk_mahin_ahmed.png'),
('Tritom Ghosh', 'Member', 'tritom@kuet.edu.bd', '+880-1XXX-XXXX', 'Dedicated player', 'image/members/tritom_ghosh.png');

-- Sample Events
INSERT INTO events (title, description, date, time, location, image, status) VALUES
('Cricket Tournament', 'Inter-university cricket championship', '2026-06-15', '10:00:00', 'KUET Ground', 'image/gallery/1.png', 'upcoming'),
('Football Match', 'Friendly match with neighboring university', '2026-06-20', '14:00:00', 'Sports Complex', 'image/gallery/2.png', 'upcoming'),
('Badminton Championship', 'Indoor badminton competition', '2026-07-01', '09:00:00', 'Sports Hall', 'image/gallery/3.png', 'upcoming'),
('Tennis Tournament', 'Singles and doubles tennis competition', '2026-07-10', '08:00:00', 'Tennis Court', 'image/gallery/4.png', 'upcoming'),
('Athletics Meet', 'Track and field events', '2026-07-15', '06:00:00', 'Stadium', 'image/gallery/5.png', 'upcoming');

-- Sample Gallery
INSERT INTO gallery (title, category, image, description) VALUES
('Cricket Tournament 2025', 'cricket', 'image/gallery/1.png', 'Highlights from the cricket tournament'),
('Football Match Kickoff', 'football', 'image/gallery/2.png', 'Players lined up for the match'),
('Badminton Players', 'badminton', 'image/gallery/3.png', 'Badminton players in action'),
('Team Celebration', 'team', 'image/gallery/4.png', 'Team celebration after winning'),
('Tennis Action', 'tennis', 'image/gallery/5.png', 'Tennis player in action'),
('Athletics Race', 'athletics', 'image/gallery/6.png', 'Sprint race competition'),
('Team Photo', 'team', 'image/gallery/7.png', 'Official team photo'),
('Victory Moment', 'event', 'image/gallery/8.png', 'Victory celebration'),
('Practice Session', 'training', 'image/gallery/9.png', 'Training session in progress'),
('Award Ceremony', 'event', 'image/gallery/10.png', 'Award presentation'),
('Group Activity', 'team', 'image/gallery/11.png', 'Team group activity'),
('Sports Day', 'event', 'image/gallery/12.png', 'Annual sports day event');
