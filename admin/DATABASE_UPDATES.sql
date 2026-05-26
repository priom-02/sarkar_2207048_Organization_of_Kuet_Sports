-- Add team field to members table if it doesn't exist
ALTER TABLE members ADD COLUMN team VARCHAR(100) DEFAULT 'General' AFTER position;

-- Create registrations table for event registrations
CREATE TABLE IF NOT EXISTS registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    department VARCHAR(100),
    team_name VARCHAR(100),
    experience_level VARCHAR(50),
    event_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_by INT,
    approved_at TIMESTAMP NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
