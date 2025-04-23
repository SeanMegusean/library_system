<?php
session_start();
include('db_connection.php');

// Check if admin is logged in (use session or authentication mechanism)
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not admin
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $req_id = $_POST['req_id'];
    $action = $_POST['action'];
    $status = ($action == 'approve') ? 'Approved' : 'Rejected';

    // Update the status of the request in the database
    $stmt = $conn->prepare("UPDATE mr_requests SET status = ? WHERE req_id = ?");
    $stmt->bind_param("si", $status, $req_id);

    if ($stmt->execute()) {
        // Log the action in the database
        logAction($req_id, $status);

        // Optionally: You can remove the request from the table, or leave it to maintain history
        // $deleteStmt = $conn->prepare("DELETE FROM mr_requests WHERE req_id = ?");
        // $deleteStmt->bind_param("i", $req_id);
        // $deleteStmt->execute();
        // $deleteStmt->close();

        // Redirect back to the manage requests page
        header("Location: admin_manage_requests.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Function to log the action in the database
function logAction($req_id, $status) {
    global $conn;

    // Get the admin ID from session
    $admin_id = $_SESSION['user_id']; 
    // Prepare and execute the log insert statement
    $stmt = $conn->prepare("INSERT INTO request_logs (req_id, action, admin_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $req_id, $status, $admin_id);

    if (!$stmt->execute()) {
        echo "Error: Could not log the action: " . $stmt->error;
    }

    $stmt->close();
}
?>
