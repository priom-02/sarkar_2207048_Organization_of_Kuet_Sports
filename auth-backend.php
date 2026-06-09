<?php
session_start();
header('Content-Type: application/json');

require_once 'admin/includes/db.php';

$response = ['success' => false, 'message' => 'Invalid request', 'debug' => []];

// ========== AUTO-LOGIN FROM REMEMBER_ME COOKIE ==========
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    $cookie_data = explode('|', $_COOKIE['remember_me']);
    if (count($cookie_data) == 2) {
        $user_id = intval($cookie_data[0]);
        $cookie_token = $cookie_data[1];
        
        // Verify the user exists and token matches
        $verify_query = "SELECT id, full_name, email, password FROM users WHERE id = ?";
        $verify_stmt = mysqli_prepare($conn, $verify_query);
        
        if ($verify_stmt) {
            mysqli_stmt_bind_param($verify_stmt, "i", $user_id);
            mysqli_stmt_execute($verify_stmt);
            $verify_result = mysqli_stmt_get_result($verify_stmt);
            
            if (mysqli_num_rows($verify_result) > 0) {
                $user = mysqli_fetch_assoc($verify_result);
                // Validate token
                $expected_token = hash('sha256', $user['email'] . $user['password']);
                
                if ($cookie_token === $expected_token) {
                    // Auto-login the user
                    $_SESSION['user'] = $user['email'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['full_name'];
                    error_log("Auto-login successful for user: " . $user['email']);
                }
            }
            mysqli_stmt_close($verify_stmt);
        }
    }
}

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
        $profile_photo = 'image/members/default.png'; // Default photo
        
        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'image/members/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Validate file type
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $file_name = $_FILES['profile_pic']['name'];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_extension, $allowed_extensions) && $_FILES['profile_pic']['size'] <= 5 * 1024 * 1024) {
                $filename = 'user_' . time() . '_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                    $profile_photo = $upload_dir . $filename;
                    $response['debug']['photo_uploaded'] = true;
                }
            }
        }
        
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
        
        // Insert new user with profile picture
        $insert_query = "INSERT INTO users (full_name, email, password, team, profile_pic) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        
        if (!$insert_stmt) {
            $response['message'] = 'Database error: ' . mysqli_error($conn);
            $response['debug']['error'] = 'Prepare failed for insert: ' . mysqli_error($conn);
            $response['debug']['query'] = $insert_query;
            error_log("Insert prepare failed: " . mysqli_error($conn));
            echo json_encode($response);
            exit;
        }
        
        mysqli_stmt_bind_param($insert_stmt, "sssss", $full_name, $email, $hashed_password, $team, $profile_photo);
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
                    // If user selected a team, add them to members table
                    if (!empty($team) && $team !== 'Other') {
                        $default_position = 'Player';
                        $empty_phone = '';
                        $empty_bio = '';
                        
                        $member_query = "INSERT INTO members (name, position, team, email, phone, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $member_stmt = mysqli_prepare($conn, $member_query);
                        
                        if ($member_stmt) {
                            mysqli_stmt_bind_param($member_stmt, "sssssss", $full_name, $default_position, $team, $email, $empty_phone, $empty_bio, $profile_photo);
                            
                            if (mysqli_stmt_execute($member_stmt)) {
                                $response['debug']['member_added'] = true;
                                $response['debug']['member_id'] = mysqli_insert_id($conn);
                                error_log("User added to members table. Team: $team");
                            } else {
                                $response['debug']['member_error'] = mysqli_error($conn);
                                error_log("Failed to add user to members: " . mysqli_error($conn));
                            }
                            mysqli_stmt_close($member_stmt);
                        }
                    }
                    
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
        $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] == '1';
        
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
                    
                    // Set "Remember Me" cookie if checked
                    if ($remember_me) {
                        // Create a secure token: user_id + email hash
                        $cookie_token = $user['id'] . '|' . hash('sha256', $user['email'] . $user['password']);
                        // Set cookie for 30 days
                        setcookie('remember_me', $cookie_token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
                        $response['debug']['cookie_set'] = true;
                    }
                    
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
        // Clear the remember_me cookie
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        $response['success'] = true;
        $response['message'] = 'Logged out successfully';
        $response['redirect'] = 'home.php';
    }
}

echo json_encode($response);
mysqli_close($conn);
?>
