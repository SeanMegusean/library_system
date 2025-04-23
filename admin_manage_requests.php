<?php
session_start();
include('db_connection.php');

// Check if user is admin (assuming you have some session mechanism)


// Fetch all meeting room requests
$sql = "SELECT * FROM mr_requests WHERE status = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Requests</title>
</head>
<body>
    <h2>Manage Meeting Room Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Room ID</th>
                <th>Student Number</th>
                <th>Date Requested</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['req_id']; ?></td>
                    <td><?php echo $row['room_id']; ?></td>
                    <td><?php echo $row['student_number']; ?></td>
                    <td><?php echo $row['date_requested']; ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <form action="process_request.php" method="POST" style="display:inline;">
                            <input type="hidden" name="req_id" value="<?= $row['req_id'] ?>">
                            <button type="submit" name="action" value="approve">Accept</button>
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="admin_dashboard.php">← Back to Admin Dashboard</a></p>
</body>
</html>