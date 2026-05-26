-- ========================================
-- UPDATE MEMBERS WITH TEAM ASSIGNMENTS
-- ========================================

-- CRICKET TEAM
UPDATE members SET team = 'Cricket Team' WHERE id = 1;
UPDATE members SET team = 'Cricket Team' WHERE id = 3;
UPDATE members SET team = 'Cricket Team' WHERE id = 4;
UPDATE members SET team = 'Cricket Team' WHERE id = 5;

-- FOOTBALL TEAM
UPDATE members SET team = 'Football Team' WHERE id = 2;
UPDATE members SET team = 'Football Team' WHERE id = 6;
UPDATE members SET team = 'Football Team' WHERE id = 7;

-- BADMINTON TEAM (if more members exist)
UPDATE members SET team = 'Badminton Team' WHERE name = 'Ahnaf Tajwar Sadi';
UPDATE members SET team = 'Badminton Team' WHERE name = 'Hasib Mahmud';

-- BASKETBALL TEAM (if more members exist)
UPDATE members SET team = 'Basketball Team' WHERE name = 'Ariful Islam Sheikh';

-- Set default team for any remaining members
UPDATE members SET team = 'General' WHERE team IS NULL OR team = '';
