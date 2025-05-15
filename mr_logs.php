<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include('db_connection.php');

// Handle export
if (isset($_GET['export'])) {
    // Set headers for Excel download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="meeting_room_logs.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Clean output buffer
    if (ob_get_length()) ob_clean();
    flush();
    
    // Start HTML output for Excel
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
    echo '<body>';
    
    // Request Logs
    echo '<table border="1">';
    echo '<tr><th colspan="6">Request Logs</th></tr>';
    echo '<tr>
            <th>Request Log ID</th>
            <th>Request ID</th>
            <th>Room ID</th>
            <th>Action</th>
            <th>Admin</th>
            <th>Timestamp</th>
          </tr>';
    
    $sql = "
        SELECT rl.*, u.student_number AS admin_number, r.room_id
        FROM request_logs rl
        JOIN mr_requests mr ON rl.req_id = mr.req_id
        JOIN users u ON rl.admin_id = u.id
        JOIN meeting_rooms r ON mr.room_id = r.room_id
        ORDER BY rl.timestamp DESC
    ";
    
    $reqresult = $conn->query($sql);
    
    while ($rqlog = $reqresult->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $rqlog['log_id'] . '</td>';
        echo '<td>' . $rqlog['req_id'] . '</td>';
        echo '<td>' . $rqlog['room_id'] . '</td>';
        echo '<td>' . $rqlog['action'] . '</td>';
        echo '<td>' . $rqlog['admin_number'] . '</td>';
        echo '<td>' . $rqlog['timestamp'] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    
    // Reservation Logs
    echo '<br><table border="1">';
    echo '<tr><th colspan="6">Reservation Logs</th></tr>';
    echo '<tr>
            <th>Reservation Log ID</th>
            <th>Reservation ID</th>
            <th>Room ID</th>
            <th>Action</th>
            <th>Admin</th>
            <th>Timestamp</th>
          </tr>';
    
    $sql = "
        SELECT rl.*, u.student_number AS admin_number, r.room_id
        FROM reserve_logs rl
        JOIN mr_reservations mr ON rl.res_id = mr.res_id
        JOIN users u ON rl.admin_id = u.id
        JOIN meeting_rooms r ON mr.room_id = r.room_id
        ORDER BY rl.timestamp DESC
    ";
    
    $resresult = $conn->query($sql);
    
    while ($rslog = $resresult->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $rslog['log_id'] . '</td>';
        echo '<td>' . $rslog['res_id'] . '</td>';
        echo '<td>' . $rslog['room_id'] . '</td>';
        echo '<td>' . $rslog['action'] . '</td>';
        echo '<td>' . $rslog['admin_number'] . '</td>';
        echo '<td>' . $rslog['timestamp'] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body></html>';
    exit;
}

// Get logs for display
$sql = "
    SELECT rl.*, u.student_number AS admin_number, r.room_id
    FROM request_logs rl
    JOIN mr_requests mr ON rl.req_id = mr.req_id
    JOIN users u ON rl.admin_id = u.id
    JOIN meeting_rooms r ON mr.room_id = r.room_id
    ORDER BY rl.timestamp DESC
";

$reqresult = $conn->query($sql);

$sql = "
    SELECT rl.*, u.student_number AS admin_number, r.room_id
    FROM reserve_logs rl
    JOIN mr_reservations mr ON rl.res_id = mr.res_id
    JOIN users u ON rl.admin_id = u.id
    JOIN meeting_rooms r ON mr.room_id = r.room_id
    ORDER BY rl.timestamp DESC
";

$resresult = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meeting Room Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">🗂️ Meeting Room Logs</h1>
            <div>
                <a href="?export=1" class="btn btn-success me-2">
                    <i class="bi bi-file-excel"></i> Export to Excel
                </a>
                <a href="admin_dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded bg-white p-3 mb-4">
            <h4>Request Logs</h4>
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Req. Log ID</th>
                        <th scope="col">Request ID</th>
                        <th scope="col">Room ID</th>
                        <th scope="col">Action</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rqlog = $reqresult->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $rqlog['log_id'] ?></td>
                            <td><?= $rqlog['req_id'] ?></td>
                            <td><?= $rqlog['room_id'] ?></td>
                            <td><?= $rqlog['action'] ?></td>
                            <td><?= $rqlog['admin_number'] ?></td>
                            <td><?= $rqlog['timestamp'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <h4>Reservation Logs</h4>
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Res. Log ID</th>
                        <th scope="col">Reservation ID</th>
                        <th scope="col">Room ID</th>
                        <th scope="col">Action</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rslog = $resresult->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $rslog['log_id'] ?></td>
                            <td><?= $rslog['res_id'] ?></td>
                            <td><?= $rslog['room_id'] ?></td>
                            <td><?= $rslog['action'] ?></td>
                            <td><?= $rslog['admin_number'] ?></td>
                            <td><?= $rslog['timestamp'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
