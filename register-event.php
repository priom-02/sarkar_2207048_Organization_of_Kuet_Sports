<?php
// Handle Event Registration Form Submission

header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "root", "", "kuet_sports");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $team_name = isset($_POST['team_name']) ? trim($_POST['team_name']) : '';
    $experience_level = isset($_POST['experience_level']) ? trim($_POST['experience_level']) : '';
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

    // Validate required fields
    if (!$full_name || !$email || !$phone || !$event_id) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    // Prepare and execute insert query
    $query = "INSERT INTO registrations (full_name, email, phone, department, team_name, experience_level, event_id, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
    
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'ssssssi', $full_name, $email, $phone, $department, $team_name, $experience_level, $event_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Registration submitted successfully! You will receive confirmation once it is approved.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting registration: ' . mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
