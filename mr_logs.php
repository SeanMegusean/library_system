<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include('db_connection.php');

// Get filter parameters
$filterDate = $_GET['date'] ?? null;
$logType = $_GET['log_type'] ?? 'all'; // Can be 'all', 'request', or 'reserve'

// Query for request logs
$reqSql = "
    SELECT rl.*, u.student_number AS admin_number, r.room_id
    FROM request_logs rl
    JOIN mr_requests mr ON rl.req_id = mr.req_id
    JOIN users u ON rl.admin_id = u.id
    JOIN meeting_rooms r ON mr.room_id = r.room_id
";

// Apply date filter if provided for request logs
if ($filterDate) {
    $reqSql .= " WHERE DATE(rl.timestamp) = '$filterDate'";
}

$reqSql .= " ORDER BY rl.timestamp DESC";
$reqResult = $conn->query($reqSql);

// Query for reservation logs
$resSql = "
    SELECT rl.*, u.student_number AS admin_number, r.room_id
    FROM reserve_logs rl
    JOIN mr_reservations mr ON rl.res_id = mr.res_id
    JOIN users u ON rl.admin_id = u.id
    JOIN meeting_rooms r ON mr.room_id = r.room_id
";

// Apply date filter if provided for reservation logs
if ($filterDate) {
    $resSql .= " WHERE DATE(rl.timestamp) = '$filterDate'";
}

$resSql .= " ORDER BY rl.timestamp DESC";
$resResult = $conn->query($resSql);

// The existing borrowings query seems unrelated to meeting room logs, keeping for reference
$today = date('Y-m-d');
$borrowFilterDate = $_GET['date'] ?? null;

$query = "
    SELECT b.campus, b.title, b.category, u.student_number, br.borrow_ref, br.borrow_date, br.status
    FROM borrowings br
    JOIN books b ON br.book_id = b.id
    JOIN users u ON br.student_number = u.student_number
";

if ($borrowFilterDate) {
    $query .= " WHERE DATE(br.borrow_date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $borrowFilterDate);
} else {
    $query .= " ORDER BY br.borrow_date DESC";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$borrowResult = $stmt->get_result();
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
        
        <!-- Enhanced Filter Form -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="get">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <label for="date" class="form-label mb-0 me-2">Filter by Date:</label>
                                <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($filterDate ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <label for="log_type" class="form-label mb-0 me-2">Log Type:</label>
                                <select name="log_type" id="log_type" class="form-select">
                                    <option value="all" <?= ($logType === 'all') ? 'selected' : ''; ?>>All Logs</option>
                                    <option value="request" <?= ($logType === 'request') ? 'selected' : ''; ?>>Request Logs</option>
                                    <option value="reserve" <?= ($logType === 'reserve') ? 'selected' : ''; ?>>Reservation Logs</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                                <a href="mr_logs.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <?php if ($logType === 'all' || $logType === 'request'): ?>
            <h4 class="mb-3">Request Logs</h4>
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
                    <?php if ($reqResult && $reqResult->num_rows > 0): ?>
                        <?php while ($rqlog = $reqResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($rqlog['log_id']) ?></td>
                                <td><?= htmlspecialchars($rqlog['req_id']) ?></td>
                                <td><?= htmlspecialchars($rqlog['room_id']) ?></td>
                                <td><?= htmlspecialchars($rqlog['action']) ?></td>
                                <td><?= htmlspecialchars($rqlog['admin_number']) ?></td>
                                <td><?= htmlspecialchars($rqlog['timestamp']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No request logs found for the selected criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>
            
            <?php if ($logType === 'all' || $logType === 'reserve'): ?>
            <h4 class="mb-3 mt-4">Reservation Logs</h4>
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
                    <?php if ($resResult && $resResult->num_rows > 0): ?>
                        <?php while ($rslog = $resResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($rslog['log_id']) ?></td>
                                <td><?= htmlspecialchars($rslog['res_id']) ?></td>
                                <td><?= htmlspecialchars($rslog['room_id']) ?></td>
                                <td><?= htmlspecialchars($rslog['action']) ?></td>
                                <td><?= htmlspecialchars($rslog['admin_number']) ?></td>
                                <td><?= htmlspecialchars($rslog['timestamp']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No reservation logs found for the selected criteria.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>