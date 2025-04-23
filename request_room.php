<?php
session_start();
include('db_connection.php');

$room_id = $_GET['room_id'];
$stmt = $conn->prepare("SELECT * FROM meeting_rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $student_number = $_SESSION['student_number'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $reason = $_POST['reason'];
    $status = "Pending";
    $request_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("
        SELECT * FROM mr_requests
        WHERE room_id = ? AND date = ? AND (
            (start_time < ? AND end_time > ?) OR
            (start_time < ? AND end_time > ?) OR
            (start_time >= ? AND end_time <= ?)
        ) AND status != 'Rejected'
    ");
    $stmt->bind_param("ssssssss", $room_id, $date, $end_time, $end_time, $start_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $conflict = $stmt->get_result();

    if ($conflict->num_rows > 0) {
        $error_message = "This room is already reserved at the selected time.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO mr_requests (room_id, student_number, date_requested, date, start_time, end_time, reason, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssss", $room_id, $student_number, $request_date, $date, $start_time, $end_time, $reason, $status);
        if ($stmt->execute()) {
            header("Location: student_RRR.php?message=Request added successfully :D");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Meeting Room</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #5E4CC2; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Request Meeting Room: <?= htmlspecialchars($room['room_name'] ?? 'Unknown') ?></h2>

    <?php if ($error_message): ?>
        <p class="error"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id) ?>">

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Start Time:</label>
        <input type="time" name="start_time" required>

        <label>End Time:</label>
        <input type="time" name="end_time" required>

        <label>Reason for Request:</label>
        <textarea name="reason" placeholder="Enter your reason..." required></textarea>

        <button type="submit">Request</button>
    </form>

    <p><a href="student_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
