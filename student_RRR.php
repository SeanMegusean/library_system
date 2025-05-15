<?php
session_start();
include('db_connection.php');
// Check if student is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php?error=Please log in first");
    exit;
}

$student_number = $_SESSION['student_number'];

// Get all meeting rooms
$sql = "SELECT * FROM meeting_rooms";
$result = $conn->query($sql);
$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}
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
                    <?php foreach ($rooms as $room) : 
                        $room_id = $room['room_id'];

                        // Check if a pending request exists for this room by the logged-in user
                        $stmt = $conn->prepare("SELECT * FROM mr_requests WHERE room_id = ? AND student_number = ? LIMIT 1");
                        $stmt->bind_param("is", $room_id, $student_number);
                        $stmt->execute();
                        $requestResult = $stmt->get_result();
                        
                        $isPending = false;
                        $isApproved = false;
                        
                        if ($requestResult->num_rows > 0) {
                            $req = $requestResult->fetch_assoc();
                            if ($req['status'] === 'Pending') {
                                $isPending = true;
                            } elseif ($req['status'] === 'Approved') {
                                $isApproved = true;
                            }
                        }
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room_id); ?></td>
                            <td><?php echo htmlspecialchars($room['Status']); ?></td>
                            <td>
                                <?php if ($room['Status'] === 'In Session'): ?>
                                    <span class="text-warning">Please wait</span>
                                <?php elseif ($room['Status'] === 'Unavailable'): ?>
                                    <!-- this is literally me fr fr fr-->
                                <?php elseif ($isPending): ?>
                                    <span class="text-secondary">Pending</span>
                                <?php elseif ($isApproved): ?>
                                    <a href="requek_aprob.php?room_id=<?= $room_id ?>" class="text-success text-decoration-none">Request Approved :D</a>
                                <?php else: ?>
                                    <a href="request_room.php?room_id=<?= $room_id ?>" class="btn btn-info btn-sm">Request</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                    <?php foreach ($rooms as $room) :
                        $room_id = $room['room_id'];

                        // Check reservation status for the student
                        $stmt = $conn->prepare("SELECT * FROM mr_reservations WHERE room_id = ? AND student_number = ? LIMIT 1");
                        $stmt->bind_param("is", $room_id, $student_number);
                        $stmt->execute();
                        $reservationResult = $stmt->get_result();

                        $isPending = false;
                        $isApproved = false;
                        
                        if ($reservationResult->num_rows > 0) {
                            $res = $reservationResult->fetch_assoc();
                            if ($res['status'] === 'PendingS') {
                                $isPending = true;
                            } elseif ($res['status'] === 'Approved') {
                                $isApproved = true;
                            }
                        }
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room_id); ?></td>
                            <td>
                                <?php if ($isPending): ?>
                                    <span class="text-secondary">Pending</span>
                                <?php elseif ($isApproved): ?>
                                    <a href="reserv_aprob.php?room_id=<?= $room_id ?>" class="text-success text-decoration-none">Reservation Approved :D</a>
                                <?php else: ?>
                                    <a href="reserve_room.php?room_id=<?= $room_id ?>" class="btn btn-success btn-sm">Reserve</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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