<?php
session_start();
header('Content-Type: application/json');

require_once 'admin/includes/db.php';

$response = ['success' => false, 'message' => 'Invalid request', 'debug' => []];

// Log all incoming requests for debugging
error_log("Auth Request: " . print_r($_POST, true));

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $response['debug']['action_received'] = $action;
    
    
    // ========== SIGN UP (REGISTRATION) ==========
    if ($action == 'signup') {
       
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $team = trim($_POST['team'] ?? '');
        
        $response['debug']['fields_received'] = [
            'full_name' => $full_name,
            'email' => $email,
            'team' => $team,
            'password_length' => strlen($password),
            'confirm_length' => strlen($confirm_password)
        ];
        
        // Validation
        if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $response['message'] = 'All fields are required';
            $response['debug']['validation'] = 'Missing fields';
            echo json_encode($response);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format';
            $response['debug']['validation'] = 'Invalid email format';
            echo json_encode($response);
            exit;
        }
        
        if ($password !== $confirm_password) {
            $response['message'] = 'Passwords do not match';
            $response['debug']['validation'] = 'Passwords do not match';
            echo json_encode($response);
            exit;
        }
        
        if (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters';
            $response['debug']['validation'] = 'Password too short';
            echo json_encode($response);
            exit;
        }
        
        // Check if email already exists
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email);
        
        if (!$stmt) {
            $response['message'] = 'Database error: ' . mysqli_error($conn);
            $response['debug']['error'] = 'Prepare failed for email check';
            echo json_encode($response);
            exit;
        }
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $response['message'] = 'Email already registered';
            echo json_encode($response);
            mysqli_stmt_close($stmt);
            exit;
        }
        mysqli_stmt_close($stmt);
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $response['debug']['hash_created'] = true;
        
        // Insert new user
        $insert_query = "INSERT INTO users (full_name, email, password, team) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        
        if (!$insert_stmt) {
            $response['message'] = 'Database error: ' . mysqli_error($conn);
            $response['debug']['error'] = 'Prepare failed for insert: ' . mysqli_error($conn);
            $response['debug']['query'] = $insert_query;
            error_log("Insert prepare failed: " . mysqli_error($conn));
            echo json_encode($response);
            exit;
        }
        
        mysqli_stmt_bind_param($insert_stmt, "ssss", $full_name, $email, $hashed_password, $team);
        $response['debug']['bind_params'] = 'Success';
        error_log("About to execute insert with: full_name=$full_name, email=$email");
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $new_id = mysqli_insert_id($conn);
            $response['debug']['insert_success'] = true;
            $response['debug']['inserted_id'] = $new_id;
            error_log("Insert successful. New user ID: $new_id");
            
            // Verify insertion
            $verify_query = "SELECT id, full_name, email FROM users WHERE id = ?";
            $verify_stmt = mysqli_prepare($conn, $verify_query);
            
            if ($verify_stmt) {
                mysqli_stmt_bind_param($verify_stmt, "i", $new_id);
                mysqli_stmt_execute($verify_stmt);
                $verify_result = mysqli_stmt_get_result($verify_stmt);
                $inserted_user = mysqli_fetch_assoc($verify_result);
                
                $response['debug']['verification'] = $inserted_user ? 'User verified in database' : 'Verification failed - user not found';
                error_log("Verification result: " . ($inserted_user ? "Success" : "Failed"));
                
                if ($inserted_user) {
                    $_SESSION['user'] = $email;
                    $_SESSION['user_id'] = $new_id;
                    $_SESSION['user_name'] = $full_name;
                    
                    $response['success'] = true;
                    $response['message'] = 'Account created successfully! Redirecting...';
                    $response['redirect'] = 'home.php';
                    $response['debug']['session_set'] = true;
                    error_log("Session set for user: $email");
                } else {
                    $response['message'] = 'Account created but verification failed. Please login.';
                    $response['debug']['session_set'] = false;
                }
                
                mysqli_stmt_close($verify_stmt);
            } else {
                $response['message'] = 'Account created but could not verify. Please login.';
                $response['debug']['verify_prepare_error'] = mysqli_error($conn);
                error_log("Verify prepare failed: " . mysqli_error($conn));
            }
        } else {
            $error_msg = mysqli_error($conn);
            $response['message'] = 'Error creating account: ' . $error_msg;
            $response['debug']['insert_error'] = $error_msg;
            error_log("Insert execution failed: " . $error_msg);
        }
        
        mysqli_stmt_close($insert_stmt);
    }
    
    // ========== SIGN IN (LOGIN) ==========
    else if ($action == 'signin') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($email) || empty($password)) {
            $response['message'] = 'Email and password are required';
            echo json_encode($response);
            exit;
        }
        
        // Query user by email
        $login_query = "SELECT id, full_name, email, password FROM users WHERE email = ?";
        $login_stmt = mysqli_prepare($conn, $login_query);
        
        if ($login_stmt) {
            mysqli_stmt_bind_param($login_stmt, "s", $email);
            mysqli_stmt_execute($login_stmt);
            $result = mysqli_stmt_get_result($login_stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user['email'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    
                    $response['success'] = true;
                    $response['message'] = 'Login successful! Redirecting...';
                    $response['redirect'] = 'home.php';
                } else {
                    $response['message'] = 'Invalid email or password';
                }
            } else {
                $response['message'] = 'Invalid email or password';
            }
            
            mysqli_stmt_close($login_stmt);
        } else {
            $response['message'] = 'Database error occurred';
            error_log("Prepare failed: " . mysqli_error($conn));
        }
    }
    
    // ========== LOGOUT ==========
    else if ($action == 'logout') {
        session_destroy();
        $response['success'] = true;
        $response['message'] = 'Logged out successfully';
        $response['redirect'] = 'home.php';
    }
}

echo json_encode($response);
mysqli_close($conn);
?>
