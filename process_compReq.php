<?php
session_start();
include('db_connection.php');

// Check if admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $req_id = $_POST['req_id'];
    $action = $_POST['action'];
    $status = ($action == 'approve') ? 'Approved' : 'Rejected';

    // Update the status of the request in the database
    $stmt = $conn->prepare("UPDATE comp_request SET status = ? WHERE req_id = ?");
    $stmt->bind_param("si", $status, $req_id);

    if ($stmt->execute()) {
        // Call the logAction function here to record the admin action
        logAction($req_id, $status);
        header("Location: admin_manage_compReq.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to log the action in the database
function logAction($req_id, $status) {
    global $conn;

    $admin_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO comp_logs (req_id, action, admin_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $req_id, $status, $admin_id);

    if (!$stmt->execute()) {
        echo "Error: Could not log the action: " . $stmt->error;
    }

    $stmt->close();
}
?>