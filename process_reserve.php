<?php
session_start();
include('db_connection.php');

// Check if admin is logged in
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = $_POST['res_id'];
    $action = $_POST['action'];
    $status = ($action == 'approve') ? 'Approved' : 'Rejected';

    // Update the status of the request in the database
    $stmt = $conn->prepare("UPDATE mr_reservations SET status = ? WHERE res_id = ?");
    $stmt->bind_param("si", $status, $res_id);

    if ($stmt->execute()) {
        // Log the action
        logAction($res_id, $status);

        // If approved, update the room status
        if ($status === 'Approved') {
            $roomStmt = $conn->prepare("SELECT room_id FROM mr_reservations WHERE res_id = ?");
            $roomStmt->bind_param("i", $res_id);
            $roomStmt->execute();
            $roomResult = $roomStmt->get_result();

            if ($roomRow = $roomResult->fetch_assoc()) {
                $room_id = $roomRow['room_id'];

                // Update room status to "In Session"
                $updateRoomStmt = $conn->prepare("UPDATE meeting_rooms SET status = 'In Session' WHERE room_id = ?");
                $updateRoomStmt->bind_param("i", $room_id);
                $updateRoomStmt->execute();
                $updateRoomStmt->close();
            }

            $roomStmt->close();
        }

        header("Location: admin_manage_reservations.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to log the action in the database
function logAction($res_id, $status) {
    global $conn;

    $admin_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO reserve_logs (res_id, action, admin_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $res_id, $status, $admin_id);

    if (!$stmt->execute()) {
        echo "Error: Could not log the action: " . $stmt->error;
    }

    $stmt->close();
}
?>
