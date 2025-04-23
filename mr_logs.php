<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include('db_connection.php');

// Get logs
$sql = "
    SELECT rl.*, u.student_number AS admin_number, r.room_id
    FROM request_logs rl
    JOIN mr_requests mr ON rl.req_id = mr.req_id
    JOIN users u ON rl.admin_id = u.id
    JOIN meeting_rooms r ON mr.room_id = r.room_id
    ORDER BY rl.timestamp DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meeting Room Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }
        .button {
            display: inline-block;
            margin: 10px 5px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>🗂️ Meeting Room Logs</h1>
    <a class="button" href="admin_dashboard.php">← Back to Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Request ID</th>
                <th>Room ID</th>
                <th>Action</th>
                <th>Admin</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($log = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $log['log_id'] ?></td>
                    <td><?= $log['req_id'] ?></td>
                    <td><?= $log['room_id'] ?></td>
                    <td><?= $log['action'] ?></td>
                    <td><?= $log['admin_number'] ?></td>
                    <td><?= $log['timestamp'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
