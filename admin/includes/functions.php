<?php

/**
 * ADMIN HELPER FUNCTIONS
 * Common functions for CRUD operations
 */

include 'db.php';

// ==========================================
// SESSION & SECURITY FUNCTIONS
// ==========================================

/**
 * Check if admin is logged in
 * Redirect to login if not
 */
function check_admin_login() {
    if (!isset($_SESSION['admin'])) {
        header("Location: " . get_admin_url("login.php"));
        exit();
    }
}

/**
 * Get admin URL (relative path)
 */
function get_admin_url($file = "") {
    $base = $_SERVER['SCRIPT_NAME'];
    $base = substr($base, 0, strrpos($base, '/') + 1);
    return $base . $file;
}

// ==========================================
// MEMBERS FUNCTIONS
// ==========================================

/**
 * Get all members from database
 */
function get_all_members($conn) {
    $query = "SELECT * FROM members ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

/**
 * Get single member by ID
 */
function get_member($conn, $id) {
    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

/**
 * Add new member
 */
function add_member($conn, $name, $position, $email, $phone, $bio, $photo) {
    $query = "INSERT INTO members (name, position, email, phone, bio, photo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $position, $email, $phone, $bio, $photo);
    return mysqli_stmt_execute($stmt);
}

/**
 * Update member
 */
function update_member($conn, $id, $name, $position, $email, $phone, $bio, $photo) {
    $query = "UPDATE members SET name=?, position=?, email=?, phone=?, bio=?, photo=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $position, $email, $phone, $bio, $photo, $id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Delete member
 */
function delete_member($conn, $id) {
    $query = "DELETE FROM members WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// ==========================================
// EVENTS FUNCTIONS
// ==========================================

/**
 * Get all events
 */
function get_all_events($conn) {
    $query = "SELECT * FROM events ORDER BY date DESC";
    return mysqli_query($conn, $query);
}

/**
 * Get single event by ID
 */
function get_event($conn, $id) {
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

/**
 * Add new event
 */
function add_event($conn, $title, $description, $date, $time, $location, $image, $status) {
    $query = "INSERT INTO events (title, description, date, time, location, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssss", $title, $description, $date, $time, $location, $image, $status);
    return mysqli_stmt_execute($stmt);
}

/**
 * Update event
 */
function update_event($conn, $id, $title, $description, $date, $time, $location, $image, $status) {
    $query = "UPDATE events SET title=?, description=?, date=?, time=?, location=?, image=?, status=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssi", $title, $description, $date, $time, $location, $image, $status, $id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Delete event
 */
function delete_event($conn, $id) {
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// ==========================================
// GALLERY FUNCTIONS
// ==========================================

/**
 * Get all gallery items
 */
function get_all_gallery($conn) {
    $query = "SELECT * FROM gallery ORDER BY uploaded_at DESC";
    return mysqli_query($conn, $query);
}

/**
 * Get single gallery item by ID
 */
function get_gallery_item($conn, $id) {
    $query = "SELECT * FROM gallery WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

/**
 * Add new gallery item
 */
function add_gallery($conn, $title, $category, $image, $description) {
    $query = "INSERT INTO gallery (title, category, image, description) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $category, $image, $description);
    return mysqli_stmt_execute($stmt);
}

/**
 * Update gallery item
 */
function update_gallery($conn, $id, $title, $category, $image, $description) {
    $query = "UPDATE gallery SET title=?, category=?, image=?, description=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $category, $image, $description, $id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Delete gallery item
 */
function delete_gallery($conn, $id) {
    $query = "DELETE FROM gallery WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// ==========================================
// UTILITY FUNCTIONS
// ==========================================

/**
 * Sanitize output for HTML display
 */
function sanitize($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Format date for display
 */
function format_date($date) {
    return date('M d, Y', strtotime($date));
}

/**
 * Redirect function
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

?>
