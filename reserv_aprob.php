<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$room_id = $_GET['room_id'];
$studentno = $_SESSION['student_number'];

$sql = "SELECT * FROM mr_reservations WHERE room_id = ? AND student_number = ? AND status = 'Approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $room_id, $studentno);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Ticket</title>
</head>
<body>
    <h1>Reservation for Room ID: <?php echo htmlspecialchars($room_id); ?></h1>
    <h3>By Student No. <?php echo htmlspecialchars($studentno); ?></h3>

    <?php if ($reservation): ?>
        <p>
            <strong>Date Reserved:</strong> <?= htmlspecialchars($reservation['date_reserved']) ?><br>
            <strong>Time:</strong> <?= htmlspecialchars($reservation['start_time']) ?> - <?= htmlspecialchars($reservation['end_time']) ?><br>
            <strong>Reason:</strong> <?= htmlspecialchars($reservation['reason']) ?><br>
            <strong>Date of Reservation:</strong> <?= htmlspecialchars($reservation['date_of_reservation']) ?><br>
            <h2>Status: <?= htmlspecialchars($reservation['status']) ?></h2>
        </p>
        <form action="cancel_reserv.php" method="POST">
        <input type="hidden" name="res_id" value="<?= htmlspecialchars($reservation['res_id']) ?>">
            <button type="submit">Cancel Reservation</button>
        </form>
    <?php else: ?>
        <p>No approved reservation found.</p>
    <?php endif; ?>

    <br>
    <a href="student_RRR.php"><-- Back</a>
</body>
</html>
