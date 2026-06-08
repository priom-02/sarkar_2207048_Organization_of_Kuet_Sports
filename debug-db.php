<?php
/**
 * Database Diagnostic Tool
 * Checks database connection, table existence, and insert functionality
 */

session_start();

// Get database connection
require_once 'admin/includes/db.php';

$output = [];

// Test 1: Check connection
$output['connection'] = [
    'status' => $conn ? 'Connected' : 'Failed',
    'message' => $conn ? 'Successfully connected to database' : mysqli_connect_error()
];

if (!$conn) {
    die(json_encode($output, JSON_PRETTY_PRINT));
}

// Test 2: Check if users table exists
$query = "SHOW TABLES LIKE 'users'";
$result = mysqli_query($conn, $query);
$table_exists = mysqli_num_rows($result) > 0;

$output['users_table'] = [
    'exists' => $table_exists,
    'message' => $table_exists ? 'Users table found' : 'Users table NOT found - Please run SQL to create it'
];

// Test 3: If table exists, check its structure
if ($table_exists) {
    $columns = [];
    $query = "DESCRIBE users";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    $output['table_structure'] = [
        'columns' => $columns,
        'message' => 'Table structure retrieved'
    ];
    
    // Test 4: Try a test insert
    $test_email = 'test_' . time() . '@test.com';
    $test_name = 'Test User ' . time();
    $test_password = password_hash('test123', PASSWORD_BCRYPT);
    
    $insert_query = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    
    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, "sss", $test_name, $test_email, $test_password);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $insert_id = mysqli_insert_id($conn);
            $output['test_insert'] = [
                'success' => true,
                'message' => 'Test insert successful!',
                'insert_id' => $insert_id,
                'email' => $test_email
            ];
            
            // Try to retrieve the inserted data
            $select_query = "SELECT * FROM users WHERE id = ?";
            $select_stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($select_stmt, "i", $insert_id);
            mysqli_stmt_execute($select_stmt);
            $select_result = mysqli_stmt_get_result($select_stmt);
            $user = mysqli_fetch_assoc($select_result);
            
            $output['data_retrieval'] = [
                'found' => $user !== null,
                'data' => $user
            ];
        } else {
            $output['test_insert'] = [
                'success' => false,
                'error' => mysqli_error($conn)
            ];
        }
        mysqli_stmt_close($insert_stmt);
    } else {
        $output['test_insert'] = [
            'success' => false,
            'error' => 'Failed to prepare statement: ' . mysqli_error($conn)
        ];
    }
    
    // Test 5: List all users in table
    $list_query = "SELECT id, full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 10";
    $list_result = mysqli_query($conn, $list_query);
    $users = [];
    while ($row = mysqli_fetch_assoc($list_result)) {
        $users[] = $row;
    }
    $output['all_users'] = [
        'count' => count($users),
        'users' => $users
    ];
}

mysqli_close($conn);

// Output results
header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
