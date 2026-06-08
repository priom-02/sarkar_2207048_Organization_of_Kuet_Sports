<?php
/**
 * Database Migration: Add Photo Column to Contact Messages
 * Run this once to add the photo column to the contact_messages table
 */

require_once 'admin/includes/db.php';

// Check if photo column exists
$check_column = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_NAME = 'contact_messages' AND COLUMN_NAME = 'photo'";
$result = mysqli_query($conn, $check_column);

if (mysqli_num_rows($result) == 0) {
    // Photo column doesn't exist, add it
    $alter_query = "ALTER TABLE contact_messages ADD COLUMN photo VARCHAR(255) NULL DEFAULT NULL";
    
    if (mysqli_query($conn, $alter_query)) {
        echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; color: #155724; border: 1px solid #c3e6cb;'>";
        echo "<h3>✅ Database Migration Successful</h3>";
        echo "<p>Photo column has been added to contact_messages table.</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; color: #721c24; border: 1px solid #f5c6cb;'>";
        echo "<h3>❌ Migration Failed</h3>";
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
        echo "</div>";
    }
} else {
    echo "<div style='background: #d1ecf1; padding: 20px; border-radius: 5px; color: #0c5460; border: 1px solid #bee5eb;'>";
    echo "<h3>ℹ️ Already Updated</h3>";
    echo "<p>Photo column already exists in contact_messages table.</p>";
    echo "</div>";
}
?>
