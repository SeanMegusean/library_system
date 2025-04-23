<?php
session_start();
include('db_connection.php');
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM meeting_rooms";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Rooms</title>
</head>
<body>
    <H2>Available Meeting Room</H2>
    <table border="1"> \\ this shi makes them border thicc
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
                // Check if a pending request exists for this room
                $stmt = $conn->prepare("SELECT * FROM mr_requests WHERE room_id = ? AND Status = 'Pending' LIMIT 1");
                $stmt->bind_param("i", $room_id);
                $stmt->execute();
                $pendingResult = $stmt->get_result();
                $isPending = $pendingResult->num_rows > 0;
            ?>
                <tr>
                    <td><?php echo $room_id; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td>
                    <?php if (($row['Status']) === 'In Session'): ?>
                        Please wait
                    <?php elseif ($isPending): ?>
                        Pending
                    <?php else: ?>
                        <a href="request_room.php?room_id=<?= $room_id ?>">Request</a>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
    </tbody>
    </table>
    <br>
    <h2>Reserve a Meeting Room</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Meeting Room</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            // | MeetR  | Action  | dito mo lagay ogos
            // | roomid | Reserve | reserve->reserve room page
        </tbody>
    </table>
    <p><a href="student_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
