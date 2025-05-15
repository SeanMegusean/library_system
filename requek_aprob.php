<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$room_id = $_GET['room_id'];
$studentno = $_SESSION['student_number'];

$sql = "SELECT * FROM mr_requests WHERE room_id = ? AND student_number = ? AND status = 'Approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $room_id, $studentno);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 p-4 rounded shadow" style="background-color: #f8f9fa;">
        <h1 class="text-center text-primary">Request Ticket</h1>
        <h5 class="text-center text-muted mb-4">Room ID: <?= htmlspecialchars($room_id) ?> | Student No: <?= htmlspecialchars($studentno) ?></h5>

        <?php if ($request): ?>
            <div class="card">
                <div class="card-body">
                    <p><strong>Date Requested:</strong> <?= htmlspecialchars($request['date_requested']) ?></p>
                    <p><strong>Reason:</strong> <?= htmlspecialchars($request['reason']) ?></p>
                    <h4 class="text-success">Status: <?= htmlspecialchars($request['status']) ?></h4>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                No approved request found.
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="student_RRR.php" class="btn btn-outline-primary">← Back to Meeting Rooms</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
