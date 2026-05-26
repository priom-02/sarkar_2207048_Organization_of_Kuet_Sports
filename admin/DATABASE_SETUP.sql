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
    team VARCHAR(100) DEFAULT 'General',
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
-- GALLERY TABLE
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
-- CONTACT MESSAGES TABLE
-- ==========================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- SAMPLE DATA (Optional - for testing)
-- ==========================================

-- Sample Members
INSERT INTO members (name, position, team, email, phone, bio, photo) VALUES
('Ahmed Hassan', 'Captain', 'Cricket Team', 'ahmed@kuet.edu.bd', '+880-1XXX-XXXX', 'Captain of cricket team and overall sports coordinator', 'image/members/ahnaf_tajwar_sadi.png'),
('Karim Ali', 'Wicket Keeper', 'Cricket Team', 'karim@kuet.edu.bd', '+880-1XXX-XXXX', 'Skilled wicket keeper with excellent reflexes', 'image/members/hasib_mahmud.png'),
('Md Abu Rayhan', 'All-rounder', 'Cricket Team', 'rayhan@kuet.edu.bd', '+880-1XXX-XXXX', 'Versatile player - batsman and bowler', 'image/members/md_abu_rayhan.png'),
('Shariar Abdullah', 'Bowler', 'Cricket Team', 'shariar@kuet.edu.bd', '+880-1XXX-XXXX', 'Fast bowler with excellent record', 'image/members/shariar-abdullah.png'),
('Fatima Khan', 'Captain', 'Football Team', 'fatima@kuet.edu.bd', '+880-1XXX-XXXX', 'Captain and midfielder of football team', 'image/members/ariful-islam-sheikh.png'),
('SK Mahin Ahmed', 'Goalkeeper', 'Football Team', 'mahin@kuet.edu.bd', '+880-1XXX-XXXX', 'Expert goalkeeper with strong defense skills', 'image/members/sk_mahin_ahmed.png'),
('Tritom Ghosh', 'Forward', 'Football Team', 'tritom@kuet.edu.bd', '+880-1XXX-XXXX', 'Striker with excellent goal-scoring ability', 'image/members/tritom_ghosh.png'),
('Ahnaf Tajwar Sadi', 'Captain', 'Badminton Team', 'ahnaf@kuet.edu.bd', '+880-1XXX-XXXX', 'Badminton champion and team leader', 'image/members/ahnaf_tajwar_sadi.png'),
('Ariful Islam Sheikh', 'Player', 'Basketball Team', 'ariful@kuet.edu.bd', '+880-1XXX-XXXX', 'Basketball player with good team coordination', 'image/members/ariful-islam-sheikh.png'),
('Hasib Mahmud', 'Vice Captain', 'Badminton Team', 'hasib@kuet.edu.bd', '+880-1XXX-XXXX', 'Vice captain and doubles expert', 'image/members/hasib_mahmud.png');

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
