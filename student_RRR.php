<?php
session_start();
include('db_connection.php');

$sql = "SELECT * FROM meeting_rooms"; // Replace with your actual table name
$result = $conn->query($sql);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <H2>Available Rooms</H2>
    <table>
    <thead>
        <tr>
            <th>Meeting Room</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['room_id']; ?></td>
                <td><?php echo $row['Status']; ?></td>
                <td><a href="request_room.php?room_id=<?= $row['room_id']?>">Request</a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    </table>
    <p><a href="student_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>