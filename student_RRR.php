<?php
session_start();
include('db_connection.php');
// Check if student is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$student_number = $_SESSION['student_number'];
$sql = "SELECT * FROM meeting_rooms";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h2 class="mb-4 text-center text-primary">Available Meeting Rooms</h2>
        
        <div class="card mb-4">
            <div class="card-header">
                Meeting Room Availability
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Meeting Room</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) : 
    $room_id = $row['room_id'];

    // Check if a pending request exists for this room by the logged-in user
    $stmt = $conn->prepare("SELECT * FROM mr_requests WHERE room_id = ? AND student_number = ? AND Status = 'Pending' LIMIT 1");
    $stmt->bind_param("ii", $room_id, $student_number);
    $stmt->execute();
    $pendingResult = $stmt->get_result();
    $isPending = $pendingResult->num_rows > 0;
?>
    <tr>
        <td><?php echo htmlspecialchars($room_id); ?></td>
        <td><?php echo htmlspecialchars($row['Status']); ?></td>
        <td>
            <?php if ($row['Status'] === 'In Session'): ?>
                <span class="text-warning">Please wait</span>
            <?php elseif ($row['Status'] === 'Unavailable'): ?>
                <!-- this is literally me fr fr fr-->
           <?php elseif ($row['Status'] === 'In Session'): ?>
                <span class="text-secondary">Pending</span>
            <?php else: ?>
                <a href="request_room.php?room_id=<?= $room_id ?>" class="btn btn-info btn-sm">Request</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <h2 class="mb-4 text-center text-primary">Reserve a Meeting Room</h2>
        
        <div class="card">
            <div class="card-header">
                Available Rooms for Reservation
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Meeting Room</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
$sql = "SELECT room_id FROM meeting_rooms"; // Just get room IDs
$result = $conn->query($sql);
$stmt = $conn->prepare("SELECT Status FROM mr_reservations WHERE room_id = ? AND student_number = ? LIMIT 1");

while ($row = $result->fetch_assoc()) :
    $room_id = $row['room_id'];

    // Check reservation status for the student
    $stmt->bind_param("ii", $room_id, $student_number);
    $stmt->execute();
    $reservationResult = $stmt->get_result();

    $status = null;
    if ($res = $reservationResult->fetch_assoc()) {
        $status = $res['Status'];
    }
?>
    <tr>
        <td><?php echo htmlspecialchars($room_id); ?></td>
        <td>
            <?php if ($status === 'Pending'): ?>
                <span class="text-secondary">Pending</span>
            <?php elseif ($status === 'Approved'): ?>
                <a href="reserv_aprob.php?room_id=<?= $room_id ?>" class="text-success text-decoration-none">Reservation Approved :D</a>
            <?php else: ?>
                <a href="reserve_room.php?room_id=<?= $room_id ?>" class="btn btn-success btn-sm">Reserve</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="student_dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
