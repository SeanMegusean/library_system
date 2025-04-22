<?php
session_start();
include('db_connection.php');

$room_id = $_GET['room_id'];
$stmt = $conn->prepare("SELECT * FROM meeting_rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$rooms = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $student_number = $_SESSION['student_number'];
    $request_date = date('Y-m-d H:i:s');
    $reason = $_POST['reason'];
    $status = "Pending";

    $stmt = $conn->prepare("
        INSERT INTO mr_requests (room_id, student_number, date_requested, reason, status)
        VALUES (?, ?, ?, ?, ?)");
    
    // Bind the parameters to the query
    $stmt->bind_param("sssss", $room_id, $student_number, $request_date, $reason, $status);
    if ($stmt->execute()) {
        header("Location: student_RRR.php?message=Request added successfully :D");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Meeting Room</title>
</head>
<body>
    <h2>Fill Up information</h2>
    <form method="POST">
        <label for="room_id">Room ID:</label>
        <span><?= htmlspecialchars($rooms['room_id']) ?></span><br><br>

        <input type="hidden" name="room_id" value="<?= htmlspecialchars($rooms['room_id']) ?>">

        <label for="reason">Reason for Request:</label><br>
        <textarea name="reason" required placeholder="Enter the reason for using the room..."></textarea><br><br>

        <button type="submit">Request</button>
    </form>
    <p><a href="student_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
