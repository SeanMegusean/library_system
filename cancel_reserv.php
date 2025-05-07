<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['res_id'])) {
    $res_id = $_POST['res_id'];
    $status = "Cancelled";

    $stmt = $conn->prepare("UPDATE mr_reservations SET status = ? WHERE res_id = ?");
    $stmt->bind_param("si", $status, $res_id);

    if ($stmt->execute()) {
        header("Location: student_RRR.php?message=Reservation cancelled successfully.");
        exit();
    } else {
        echo "Error updating reservation: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: student_RRR.php?error=Invalid request.");
    exit();
}
?>
