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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">🗂️ Meeting Room Logs</h1>
            <a href="admin_dashboard.php" class="btn btn-success">← Back to Dashboard</a>
        </div>

        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Log ID</th>
                        <th scope="col">Request ID</th>
                        <th scope="col">Room ID</th>
                        <th scope="col">Action</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Timestamp</th>
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
        </div>
    </div>
</body>
</html>