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
    $date_reserved = $_POST['date_reserved'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $reason = $_POST['reason'];
    $status = "Pending";
    $date_of_reservation = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("
        SELECT * FROM mr_reservations
        WHERE room_id = ? AND date_reserved = ? AND (
            (start_time < ? AND end_time > ?) OR
            (start_time < ? AND end_time > ?) OR
            (start_time >= ? AND end_time <= ?)
        ) AND status != 'Rejected'
    ");
    $stmt->bind_param("ssssssss", $room_id, $date_reserved, $end_time, $end_time, $start_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $conflict = $stmt->get_result();

    if ($conflict->num_rows > 0) {
        $error_message = "This room is already reserved at the selected time.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO mr_reservations (room_id, student_number, date_reserved, start_time, end_time, reason, date_of_reservation, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssss", $room_id, $student_number, $date_reserved, $start_time, $end_time, $reason, $date_of_reservation, $status);
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserve Meeting Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 p-4 rounded shadow" style="background: #f8f9fa;">
        <h2 class="text-center text-primary mb-4">Reserve Meeting Room: <?= htmlspecialchars($room['room_id'] ?? 'Unknown') ?></h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id) ?>">

            <div class="mb-3">
                <label for="date_reserved" class="form-label">Date:</label>
                <input type="date" name="date_reserved" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time:</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">End Time:</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Reserve:</label>
                <textarea name="reason" class="form-control" rows="4" placeholder="Enter your reason..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">Reserve</button>
        </form>

        <div class="mt-3 text-center">
            <a href="student_rrr.php" class="btn btn-outline-primary">← Back to Meeting Rooms</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
