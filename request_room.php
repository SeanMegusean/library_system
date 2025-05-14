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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Meeting Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 p-4 rounded shadow-lg" style="background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);">
        <h2 class="text-center text-primary mb-4">Request Meeting Room</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID:</label>
                <span class="form-control-plaintext"><?= htmlspecialchars($rooms['room_id']) ?></span>
                <input type="hidden" name="room_id" value="<?= htmlspecialchars($rooms['room_id']) ?>">
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Request:</label>
                <textarea class="form-control" name="reason" required placeholder="Enter the reason for using the room..." rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-outline-success w-100">Submit Request</button>
        </form>

        <div class="mt-3 text-center">
            <a href="student_RRR.php" class="btn btn-outline-primary">← Back to Meeting Rooms</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
