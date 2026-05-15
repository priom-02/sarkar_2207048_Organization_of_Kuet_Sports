<?php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "kuet_sports";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    // Log error securely (don't expose to users)
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Unable to connect to the database. Please try again later.");
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

?>
