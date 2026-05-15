<?php
// Admin - View and Manage Event Registrations

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "kuet_sports");
if (!$conn) {
    die("Database connection failed!");
}

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $registration_id = intval($_POST['registration_id']);
    $action = $_POST['action']; // 'approve' or 'reject'
    $admin_id = $_SESSION['admin_id'];
    
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    
    $query = "UPDATE registrations SET status = ?, approved_by = ?, approved_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sii', $status, $admin_id, $registration_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Fetch registration to get email
        $emailQuery = "SELECT email, full_name FROM registrations WHERE id = ?";
        $emailStmt = mysqli_prepare($conn, $emailQuery);
        mysqli_stmt_bind_param($emailStmt, 'i', $registration_id);
        mysqli_stmt_execute($emailStmt);
        $result = mysqli_stmt_get_result($emailStmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row) {
            $email = $row['email'];
            $name = $row['full_name'];
            
            // Send confirmation email
            $subject = ($action === 'approve') ? "Event Registration Approved" : "Event Registration Status";
            $message = ($action === 'approve') 
                ? "Hi $name, your event registration has been approved! See you at the event." 
                : "Hi $name, your event registration could not be approved at this time.";
            
            // Note: Configure your email settings here
            // mail($email, $subject, $message);
        }
        
        $success_msg = ($action === 'approve') ? "Registration approved!" : "Registration rejected!";
    }
    
    mysqli_stmt_close($stmt);
}

// Fetch filter option
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'pending';
$filters = ['all', 'pending', 'approved', 'rejected'];
if (!in_array($filter, $filters)) $filter = 'pending';

// Fetch registrations
$whereClause = ($filter === 'all') ? "" : "WHERE r.status = '$filter'";
$query = "SELECT r.*, e.title as event_title FROM registrations r LEFT JOIN events e ON r.event_id = e.id $whereClause ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
$registrations = [];

while ($row = mysqli_fetch_assoc($result)) {
    $registrations[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations - Admin Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            margin: 0;
            color: #1a1a1a;
        }

        .back-link {
            display: inline-block;
            padding: 8px 16px;
            background: #e3f2fd;
            color: #0066cc;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #0066cc;
            color: white;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn.active {
            background: #0066cc;
            color: white;
            border-color: #0066cc;
        }

        .registrations-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            font-size: 13px;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-reject {
            background: #dc3545;
            color: white;
        }

        .btn-reject:hover {
            background: #c82333;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media (max-width: 768px) {
            table {
                font-size: 11px;
            }

            th, td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="page-header">
            <h1>Event Registrations</h1>
            <a href="../dashboard.php" class="back-link">← Back to Dashboard</a>
        </div>

        <?php if (isset($success_msg)): ?>
            <div class="success-msg"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <div class="filter-tabs">
            <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
            <a href="?filter=pending" class="filter-btn <?php echo $filter === 'pending' ? 'active' : ''; ?>">Pending</a>
            <a href="?filter=approved" class="filter-btn <?php echo $filter === 'approved' ? 'active' : ''; ?>">Approved</a>
            <a href="?filter=rejected" class="filter-btn <?php echo $filter === 'rejected' ? 'active' : ''; ?>">Rejected</a>
        </div>

        <div class="registrations-table">
            <?php if (count($registrations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Event</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reg['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                <td><?php echo htmlspecialchars($reg['phone']); ?></td>
                                <td><?php echo htmlspecialchars($reg['event_title'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($reg['department'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $reg['status']; ?>">
                                        <?php echo ucfirst($reg['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($reg['created_at'])); ?></td>
                                <td>
                                    <?php if ($reg['status'] === 'pending'): ?>
                                        <div class="action-buttons">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="registration_id" value="<?php echo $reg['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-approve">Approve</button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="registration_id" value="<?php echo $reg['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-reject">Reject</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #999;">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No registrations found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
