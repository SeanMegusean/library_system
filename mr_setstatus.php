<?php
session_start();
include('db_connection.php');

// Check if admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $room_id = $_POST['room_id'];

    // Update the status of the request in the database
    $stmt = $conn->prepare("UPDATE meeting_rooms SET status = ? WHERE room_id = ?");
    $stmt->bind_param("si", $status, $room_id);

    if ($stmt->execute()) {
        header("Location: admin_mr_status.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
